<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Compiler;

use Exception;
use Morebec\ObjectGenerator\Domain\Compiler\PHPObjectCompiler;
use Morebec\ObjectGenerator\Domain\Exception\FileNotFoundException;
use Morebec\ObjectGenerator\Domain\ObjectDumper;
use Morebec\ObjectGenerator\Domain\Validation\ObjectSchemaValidator;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidLayerObjectSchemaException;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidProjectConfigurationException;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\LayerObjectTemplateHandlerNotFoundException;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\TemplateHandlerException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectCompilationRequest;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectSchema;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\NamespaceVO;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\LayerObjectSchemaTemplateHandlerFinder;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader\LayerObjectSchemaDataLoaderInterface;
use Morebec\ValueObjects\File\Path;
use Psr\Log\LoggerInterface;
use Stringy\Stringy as Str;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Compiles layer objects from layer objects
 */
class LayerObjectCompiler
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
     * @var LayerObjectSchemaTemplateHandlerFinder
     */
    private $handlerFinder;
    /**
     * @var LayerObjectSchemaDataLoaderInterface
     */
    private $schemaDataLoader;
    /**
     * @var LayerObjectSchemaTemplateHandlerFinder
     */
    private $templateHandlerFinder;

    /**
     * Queue of objects to be compiled
     * @var array
     */
    private $objectQueue;


    public function __construct(
        LayerObjectSchemaDataLoaderInterface $schemaDataLoader,
        LayerObjectSchemaTemplateHandlerFinder $handlerFinder,
        LayerObjectSchemaTemplateHandlerFinder $templateHandlerFinder,
        LoggerInterface $logger
    )
    {
        $this->phpCompiler = new PHPObjectCompiler();

        $this->filesystem = new Filesystem();
        $this->logger = $logger;
        $this->handlerFinder = $handlerFinder;
        $this->schemaDataLoader = $schemaDataLoader;
        $this->templateHandlerFinder = $templateHandlerFinder;

        $this->objectQueue = [];
    }

    /**
     * @param AbstractLayer $layer
     * @param LayerObjectConfiguration $objectConfiguration
     * @param string $layerDirectoryName
     * @throws FileNotFoundException
     * @throws InvalidLayerObjectSchemaException
     * @throws InvalidProjectConfigurationException
     * @throws LayerObjectTemplateHandlerNotFoundException
     */
    public function enqueueObject(
        AbstractLayer $layer,
        LayerObjectConfiguration $objectConfiguration, 
        string $layerDirectoryName
    )
    {
        $request = $this->buildCompilationRequest($layer, $objectConfiguration, $layerDirectoryName);
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
     * Builds a Compilation request for a given Layer Object Configuration
     * @param AbstractLayer $layer layer owning the object
     * @param LayerObjectConfiguration $objectConfiguration configuration of the object
     * @param string $layerDirectoryName name of the layer directory in which the object should be compiled
     * @return LayerObjectCompilationRequest
     * @throws InvalidLayerObjectSchemaException
     * @throws InvalidProjectConfigurationException
     * @throws LayerObjectTemplateHandlerNotFoundException
     * @throws FileNotFoundException
     * @throws TemplateHandlerException
     */
    private function buildCompilationRequest(
        AbstractLayer $layer,
        LayerObjectConfiguration $objectConfiguration,
        string $layerDirectoryName
    ): LayerObjectCompilationRequest
    {
        $schemaFile = $objectConfiguration->getSchemaFile();

        // Determine Target directory name
        $dirname = $this->determineTargetDirectoryName($objectConfiguration, $layerDirectoryName);

        // Determine namespace
        $namespace = $this->determineNamespace($layer, $dirname);

        // Determine target location
        $objectName = Str::create($schemaFile->getFilename())->upperCaseFirst();
        $targetFileDir = $layer->getDirectory() . "/$dirname";

        // Create directory where the file will be located
        $this->filesystem->mkdir($targetFileDir);

        // Target File Path
        $targetFilePath = new Path("$targetFileDir/$objectName.php");


        // Load Object Schema
        $data = $this->schemaDataLoader->loadFromFile($schemaFile);

        // Handle template if any
        $data = $this->handleTemplate($layer, $objectConfiguration, $data);

        // Validate Data
        $validator = new ObjectSchemaValidator();
        $data = $validator->validate($objectName, $data);

        // Create schema
        $schema = $this->buildSchema($objectName, $data, $namespace);

        // Handle essence if any
        if($objectConfiguration->hasEssence()) {
            $this->applyEssencePattern($schema);
        }

        // Create Request and return it
        return new LayerObjectCompilationRequest(
            $schema,
            $namespace,
            LayerObjectFile::makeFromPath(new Path($targetFilePath))
        );
    }

    private function compileObjectFromRequest(LayerObjectCompilationRequest $request) {
        $objectSchema = $request->getLayerObjectSchema();

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
     * @param AbstractLayer $layer
     * @param string $targetDirectoryName
     * @return NamespaceVO
     */
    private function determineNamespace(AbstractLayer $layer, string $targetDirectoryName): NamespaceVO
    {
        return $layer->getNamespace()->appendString(
            Str::create($targetDirectoryName)->replace('/', '\\')->replace('\\\\', '\\')
        );
    }

    /**
     * Determine the target directory name for the object
     * @param LayerObjectConfiguration $objectConfiguration
     * @param string $layerDirectoryName
     * @return string
     */
    private function determineTargetDirectoryName(LayerObjectConfiguration $objectConfiguration, string $layerDirectoryName): string
    {
        $subDir = $objectConfiguration->getSubDirectoryName();
        $dirname = $layerDirectoryName;
        if ($subDir) {
            $dirname = "$dirname/$subDir";
        }
        return $dirname;
    }

    /**
     * @param AbstractLayer $layer
     * @param LayerObjectConfiguration $objectConfiguration
     * @param array $data
     * @return array
     * @throws InvalidProjectConfigurationException
     * @throws LayerObjectTemplateHandlerNotFoundException
     * @throws TemplateHandlerException
     */
    private function handleTemplate(AbstractLayer $layer, LayerObjectConfiguration $objectConfiguration, array $data): array
    {
        if(!array_key_exists('template', $data)) return $data;

        $template = $data['template'];
        $handler = $this->templateHandlerFinder->getHandler($layer->getProjectConfiguration(), $template);
        try {
            include $handler;
            /** @var Callable $handleTemplate */
            $data = $handleTemplate($objectConfiguration, $data);
        } catch(Exception $e) {
            throw new TemplateHandlerException($e->getMessage());
        }
        return $data;
    }

    /**
     * Applies the essence pattern on a base object schema
     * and returns it essence
     * @param LayerObjectSchema $baseObject
     * @return LayerObjectSchema
     */
    private function applyEssencePattern(LayerObjectSchema $baseObject): LayerObjectSchema
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
        foreach($essence->getProperties() as $prop) {
            if($prop->getVisibility() === 'private') {
                $prop->setVisibility('protected');
            }
        }

        // Make all private methods protected on the essence
        foreach($essence->getMethods() as $method) {
            if($method->getVisibility() === 'private') {
                $method->setVisibility('protected');
            }
        }

        return $essence;
    }

    /**
     * @param Str $objectName
     * @param array $data
     * @param NamespaceVO $namespace
     * @return LayerObjectSchema
     */
    private function buildSchema(Str $objectName, array $data, NamespaceVO $namespace): LayerObjectSchema
    {
        $schema = LayerObjectSchema::createFromArray($objectName, $data);
        $schema->addAnnotation('@Orkestra\Generated');

        // Apply Namespace to schema
        $schema->setNamespace((string)$namespace);
        $schema->setName($objectName);
        return $schema;
    }
}