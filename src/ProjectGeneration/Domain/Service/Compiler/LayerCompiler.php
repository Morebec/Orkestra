<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Compiler;


use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject\LayerObjectCompilationRequest;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject\LayerObjectConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject\LayerObjectFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject\LayerObjectSchema;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Loader\LayerObjectSchemaLoaderInterface;
use Morebec\ValueObjects\File\Path;
use Psr\Log\LoggerInterface;
use Stringy\Stringy as Str;
use Symfony\Component\Filesystem\Filesystem;

/**
 * A Layer compiler is responsible for compiling all the resources of a layer 
 * and creating the necessary directories to support them.
 */
class LayerCompiler
{
    /**
     * @var Filesystem 
     */
    protected $filesystem;
    
    /**
     * @var LayerObjectCompilerInterface
     */
    protected $objectCompiler;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var LayerObjectSchemaLoaderInterface
     */
    private $objectSchemaLoader;

    public function __construct(
        LayerObjectCompilerInterface $objectCompiler,
        LayerObjectSchemaLoaderInterface $objectSchemaLoader,
        LoggerInterface $logger
    )
    {
        $this->filesystem = new Filesystem();
        $this->objectCompiler = $objectCompiler;
        $this->logger = $logger;
        $this->objectSchemaLoader = $objectSchemaLoader;
    }

    /**
     * Compiles a layer
     * @param AbstractLayer $layer
     */
    public function compile(AbstractLayer $layer)
    {
        $this->compileObjects($layer);
    }

    /**
     * Compiles the objects of the layer
     * @param AbstractLayer $layer
     */
    public function compileObjects(AbstractLayer $layer)
    {
        $layerConfiguration = $layer->getConfiguration();
        /** @var LayerObjectConfiguration $objectConfiguration */
        foreach ($layerConfiguration->getLayerObjectConfigurations() as $key => $objectConfigurations) {
            foreach ($objectConfigurations as $objConfig) {
                $this->compileObject($layer, $key, $objConfig);
            }
        }
    }

    /**
     * @param AbstractLayer $layer
     * @param string $key
     * @param LayerObjectConfiguration $objectConfiguration
     */
    protected function compileObject(AbstractLayer $layer, string $key, LayerObjectConfiguration $objectConfiguration) {
        $request = $this->buildObjectCompilationRequest($layer, $key, $objectConfiguration);
        $this->compileObjectRequest($request);
    }

    protected  function compileObjectRequest(LayerObjectCompilationRequest $request)
    {

        $this->logger->info(sprintf('Compiling Object %s ...',
            $request->getNamespace() . '\\' .$request->getLayerObjectSchema()->getName()
        ));
        $this->objectCompiler->compileObject($request);
    }

    /**
     * Builds a LayerObjectCompilationRequest
     * @param AbstractLayer $layer
     * @param string $configurationKey under which the Object configuration is defined
     * @param LayerObjectConfiguration $objectConfiguration
     * @return LayerObjectCompilationRequest
     */
    protected function buildObjectCompilationRequest(
        AbstractLayer $layer,
        string $configurationKey,
        LayerObjectConfiguration $objectConfiguration
    ): LayerObjectCompilationRequest
    {
        $schemaFile = $objectConfiguration->getSchemaFile();

        // Determine Target Location
        $subDir = $objectConfiguration->getSubDirectoryName();
        $objectTargetDirectoryName = $this->mapLayerConfigurationKeyToLayerSubDirectoryName($configurationKey);
        $dirname = $objectTargetDirectoryName ;
        if($subDir) {
            $dirname = "$dirname/$subDir";
        }

        // Determine namespace
        $namespace = $layer->getNamespace()->appendString(
            Str::create($dirname)->replace('/', '\\')->replace('\\\\', '\\')
        );

        // Determine target location
        $objectName = Str::create($schemaFile->getFilename())->toTitleCase();
        $targetFileDir = $layer->getDirectory() . "/$dirname";
        $targetFilePath = new Path("$targetFileDir/$objectName.php");

        // Create directory where the file will be located
        $this->filesystem->mkdir($targetFileDir);

        // Load Object Schema
        $schema = $this->objectSchemaLoader->loadFromFile($schemaFile);

        // Apply Namespace to schema
        $schema->setNamespace((string)$namespace);
        $schema->setName($objectName);

        // Create Request and return it
        $objectCompilationRequest = new LayerObjectCompilationRequest(
            $schema,
            $namespace,
            LayerObjectFile::makeFromPath(new Path($targetFilePath))
        );


        // Compile Essence if necessary
        // If the entity has an essence, but that essence does not exist,
        // We will need to compile both the base and the essence
        // If the entity's essence exists
        // We will only need to compile the essence and not the base entity
        $essenceObjectName = 'Abstract' . $objectName . 'Essence';
        $essencePath = new Path("$targetFileDir/$essenceObjectName.php");
        $essenceFile = LayerObjectFile::makeFromPath(new Path($essencePath));
        $essenceExists = $essenceFile->exists();

        if($objectConfiguration->hasEssence()) {
            $essence = $this->applyEssencePattern($schema, $essenceObjectName);
            $essenceCompileRequest = new LayerObjectCompilationRequest(
                $essence,
                $namespace,
                $essenceFile
            );
            if($essenceExists && $objectCompilationRequest->getOutFile()->exists()) {
                return $essenceCompileRequest;
            }
            $this->compileObjectRequest($essenceCompileRequest);
        }


        return $objectCompilationRequest;
    }

    /**
     * Applies the essence pattern on a base object schema
     * and returns it essence
     * @param LayerObjectSchema $baseObject
     * @return LayerObjectSchema
     */
    public function applyEssencePattern(LayerObjectSchema $baseObject, string $essenceName): LayerObjectSchema
    {
        $essence = clone $baseObject;
        $baseObjectName = $baseObject->getName();

        // Setup Essence
        $essence->setName($essenceName);
        $essence->setAbstract(true);

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
     * Maps a layer's configuration key to a layer's sub directory
     * @param string $key
     * @return string
     */
    protected function mapLayerConfigurationKeyToLayerSubDirectoryName(string $key): string
    {
        return Str::create($key)->toTitleCase();
    }
}
