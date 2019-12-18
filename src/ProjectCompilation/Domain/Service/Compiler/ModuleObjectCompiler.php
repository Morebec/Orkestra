<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Compiler;

use Exception;
use Morebec\ObjectGenerator\Domain\Compiler\PHPObjectCompiler;
use Morebec\ObjectGenerator\Domain\Exception\FileNotFoundException;
use Morebec\ObjectGenerator\Domain\ObjectDumper;
use Morebec\ObjectGenerator\Domain\Validation\ObjectSchemaValidator;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidModuleObjectSchemaException;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidProjectConfigurationException;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\ModuleObjectTemplateHandlerNotFoundException;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\TemplateHandlerException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\ModuleObjectCompilationRequest;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\ModuleObjectSchema;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\AbstractModuleObjectConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\NamespaceVO;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project\ProjectConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\ModuleObjectSchemaTemplateHandlerFinder;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader\ModuleObjectSchemaDataLoaderInterface;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;
use Psr\Log\LoggerInterface;
use Stringy\Stringy as Str;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Compiles module objects from module object schemas
 */
class ModuleObjectCompiler
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ModuleObjectSchemaDataLoaderInterface
     */
    private $schemaDataLoader;

    /**
     * @var ModuleObjectSchemaTemplateHandlerFinder
     */
    private $templateHandlerFinder;

    /**
     * Queue of objects to be compiled
     * @var array
     */
    private $objectQueue;

    /**
     * @var PHPObjectCompiler
     */
    private $phpCompiler;


    /**
     * ModuleObjectCompiler constructor.
     * @param ModuleObjectSchemaDataLoaderInterface $schemaDataLoader
     * @param ModuleObjectSchemaTemplateHandlerFinder $templateHandlerFinder
     * @param LoggerInterface $logger
     */
    public function __construct(
        ModuleObjectSchemaDataLoaderInterface $schemaDataLoader,
        ModuleObjectSchemaTemplateHandlerFinder $templateHandlerFinder,
        LoggerInterface $logger
    ) {
        $this->phpCompiler = new PHPObjectCompiler();

        $this->filesystem = new Filesystem();
        $this->logger = $logger;
        $this->schemaDataLoader = $schemaDataLoader;
        $this->templateHandlerFinder = $templateHandlerFinder;

        $this->objectQueue = [];
    }

    /**
     * @param ProjectConfiguration $projectConfiguration
     * @param AbstractModuleObjectConfiguration $objectConfiguration
     * @param NamespaceVO $targetNamespace
     * @param string $targetDirectoryNameUnderBase
     * @param Directory $baseDirectory
     *
     * @throws FileNotFoundException
     * @throws InvalidModuleObjectSchemaException
     * @throws InvalidProjectConfigurationException
     * @throws ModuleObjectTemplateHandlerNotFoundException
     * @throws TemplateHandlerException
     */
    public function enqueueObject(
        ProjectConfiguration $projectConfiguration,
        AbstractModuleObjectConfiguration $objectConfiguration,
        NamespaceVO $targetNamespace,
        string $targetDirectoryNameUnderBase,
        Directory $baseDirectory
    ) {
        $request = $this->buildCompilationRequest(
            $projectConfiguration,
            $objectConfiguration,
            $targetNamespace,
            $targetDirectoryNameUnderBase,
            $baseDirectory
        );
        $this->objectQueue[] = $request;
    }


    /**
     * Compile the queue of objects
     */
    public function compileQueue(): void
    {
        foreach ($this->objectQueue as $request) {
            $this->compileObjectFromRequest($request);
        }

        // Clear queue
        $this->objectQueue = [];
    }

    /**
     * Builds a Compilation request for a given Module Object Configuration
     * @param ProjectConfiguration $projectConfiguration
     * @param AbstractModuleObjectConfiguration $objectConfiguration configuration of the object
     * @param NamespaceVO $targetNamespace the base target namespace of the object, chances of being expended
     *                                     according to the object configuration
     * @param string $targetDirectoryNameUnderBase the target directory of the object, chances of being expended
     *                                      according to configuration
     *
     * @param Directory $baseDirectory
     * @return ModuleObjectCompilationRequest
     * @throws FileNotFoundException
     * @throws InvalidModuleObjectSchemaException
     * @throws InvalidProjectConfigurationException
     * @throws ModuleObjectTemplateHandlerNotFoundException
     * @throws TemplateHandlerException
     */
    private function buildCompilationRequest(
        ProjectConfiguration $projectConfiguration,
        AbstractModuleObjectConfiguration $objectConfiguration,
        NamespaceVO $targetNamespace,
        string $targetDirectoryNameUnderBase,
        Directory $baseDirectory
    ): ModuleObjectCompilationRequest {
        $schemaFile = $objectConfiguration->getSchemaFile();

        // Determine Target directory name
        $targetDirectoryNameUnderBase = $this->expendTargetDirectory($objectConfiguration, $targetDirectoryNameUnderBase);

        // Determine namespace
        $namespace = $this->expendNamespace($targetNamespace, $targetDirectoryNameUnderBase);

        // Determine target location
        $objectName = Str::create($schemaFile->getFilename())->upperCaseFirst();
        $targetFileDir = "{$baseDirectory}/{$targetDirectoryNameUnderBase}";

        // Create directory where the file will be located
        $this->filesystem->mkdir($targetFileDir);

        // Target File Path
        $targetFilePath = new Path("$targetFileDir/$objectName.php");


        // Load Object Schema
        $data = $this->schemaDataLoader->loadFile($schemaFile);

        // Handle template if any
        $data = $this->handleTemplate($projectConfiguration, $objectConfiguration, $data);

        // Validate Data
        $validator = new ObjectSchemaValidator();
        $data = $validator->validate($objectName, $data);

        // Create schema
        $schema = $this->buildSchema($objectName, $data, $namespace);

        // Handle essence if any
        if ($objectConfiguration->hasEssence()) {
            $this->applyEssencePattern($schema);
        }

        // Create Request and return it
        return new ModuleObjectCompilationRequest(
            $schema,
            $namespace,
            LayerObjectFile::makeFromPath(new Path($targetFilePath))
        );
    }

    private function compileObjectFromRequest(ModuleObjectCompilationRequest $request)
    {
        $objectSchema = $request->getModuleObjectSchema();

        $objectName = $objectSchema->getNamespace() . '\\' . $objectSchema->getName();
        $outFile = $request->getOutFile();
        $this->logger->info("Compiling $objectName to $outFile ...");

        // Compile
        $object = $this->phpCompiler->compile($objectSchema);

        // Convert to code
        $objectDumper = new ObjectDumper();
        $code = $objectDumper->dump($objectSchema, $object);

        // Write content
        file_put_contents((string)$outFile, $code);
    }

    /**
     * Determine the namespace of the object
     * @param NamespaceVO $targetNamespace
     * @param string $targetDirectoryName
     * @return NamespaceVO
     */
    private function expendNamespace(NamespaceVO $targetNamespace, string $targetDirectoryName): NamespaceVO
    {
        return $targetNamespace->appendString(
            Str::create($targetDirectoryName)->replace('/', '\\')->replace('\\\\', '\\')
        );
    }

    /**
     * Determine the target directory name for the object
     * @param AbstractModuleObjectConfiguration $objectConfiguration
     * @param string $targetDirectoryName
     * @return string
     */
    private function expendTargetDirectory(
        AbstractModuleObjectConfiguration $objectConfiguration,
        string $targetDirectoryName
    ): string {
        $dirname = $targetDirectoryName;
        $subDir = $objectConfiguration->getSubDirectoryName();
        if ($subDir) {
            $dirname = "$dirname/$subDir";
        }
        return $dirname;
    }

    /**
     * @param ProjectConfiguration $projectConfiguration
     * @param AbstractModuleObjectConfiguration $objectConfiguration
     * @param array $data
     * @return array
     * @throws InvalidProjectConfigurationException
     * @throws ModuleObjectTemplateHandlerNotFoundException
     * @throws TemplateHandlerException
     */
    private function handleTemplate(
        ProjectConfiguration $projectConfiguration,
        AbstractModuleObjectConfiguration $objectConfiguration,
        array $data
    ): array {
        if (!array_key_exists('template', $data)) {
            return $data;
        }

        $template = $data['template'];
        $handler = $this->templateHandlerFinder->getHandler($projectConfiguration, $template);
        try {
            include $handler;
            /** @var Callable $handleTemplate */
            $data = $handleTemplate($objectConfiguration, $data);
        } catch (Exception $e) {
            throw new TemplateHandlerException($e->getMessage());
        }
        return $data;
    }

    /**
     * Applies the essence pattern on a base object schema
     * and returns it essence
     * @param ModuleObjectSchema $baseObject
     * @return ModuleObjectSchema
     */
    private function applyEssencePattern(ModuleObjectSchema $baseObject): ModuleObjectSchema
    {
        $essence = clone $baseObject;
        $baseObjectName = $baseObject->getName();

        // Setup Essence
        $essenceName = 'Abstract' . $baseObjectName . 'Essence';
        $essence->setName($essenceName);
        $essence->setAbstract(true);
        $essence->setDescription("Essence for $baseObjectName");

        // Strip the base object from anything
        $baseObject->setProperties([]);
        $baseObject->setAnnotations([]);
        $baseObject->setMethods([]);
        $baseObject->setExtends($essenceName);
        $baseObject->setImplements([]);

        // Make all private properties protected on the essence
        foreach ($essence->getProperties() as $prop) {
            if ($prop->getVisibility() === 'private') {
                $prop->setVisibility('protected');
            }
        }

        // Make all private methods protected on the essence
        foreach ($essence->getMethods() as $method) {
            if ($method->getVisibility() === 'private') {
                $method->setVisibility('protected');
            }
        }

        return $essence;
    }

    /**
     * @param Str $objectName
     * @param array $data
     * @param NamespaceVO $namespace
     * @return ModuleObjectSchema
     */
    private function buildSchema(Str $objectName, array $data, NamespaceVO $namespace): ModuleObjectSchema
    {
        $schema = ModuleObjectSchema::createFromArray($objectName, $data);
        $schema->addAnnotation('@Orkestra\Generated');

        // Apply Namespace to schema
        $schema->setNamespace((string)$namespace);
        $schema->setName($objectName);
        return $schema;
    }
}
