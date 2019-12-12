<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Compiler;


use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Domain\DomainLayer;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject\LayerObjectConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject\LayerObjectSchemaFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\NamespaceVO;
use Morebec\ValueObjects\File\File;
use Morebec\ValueObjects\File\Path;
use Psr\Log\LoggerInterface;
use Stringy\Stringy as Str;
use Symfony\Component\Filesystem\Filesystem;

/**
 * A Layer compiler is responsible for compiling all the resources of a layer 
 * and creating the necessary directories to support them.
 */
abstract class LayerCompiler
{
    /**
     * @var Filesystem 
     */
    protected $filesystem;
    
    /**
     * @var LayerObjectCompilerInterface
     */
    protected $objectCompiler;
    
    public function __construct(
        LayerObjectCompilerInterface $objectCompiler,
        LoggerInterface $logger
    )
    {
        $this->filesystem = new Filesystem();
        $this->objectCompiler = $objectCompiler;
        $this->logger = $logger;
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
     * @param DomainLayer $layer
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
     * @param DomainLayer $layer
     * @param string $key
     * @param LayerObjectConfiguration $objectConfiguration
     */
    protected function compileObject(DomainLayer $layer, string $key, LayerObjectConfiguration $objectConfiguration) {

        $this->logger->info(sprintf('Compiling Object %s ...', $objectConfiguration->getSchemaFile()->getBasename()));

        $schema = $objectConfiguration->getSchemaFile();

        // Determine location of schema file
        $schemaFile = $objectConfiguration->getSchemaFile();



        // Determine Target Location
        $subDir = $objectConfiguration->getSubDirectoryName();
        $objectTargetDirectoryName = $this->mapLayerConfigurationKeyToLayerSubDirectoryName($key);
        $dirname = "$objectTargetDirectoryName/$subDir";

        $objectName = Str::create($schemaFile->getFilename())->toTitleCase();
        $targetFileDir = $layer->getDirectory() . "/$dirname";
        $targetFilePath = new Path("$targetFileDir/$objectName.php");


        // Determine namespace
        $namespace = $layer->getNamespace()->appendString(
            Str::create($dirname)->replace('/', '\\')->replace('\\\\', '\\')
        );

        // Create directory
        $this->filesystem->mkdir($targetFileDir);

        $this->compileObjectSchemaToFile($schemaFile, $namespace, File::fromStringPath($targetFilePath));
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

    /**
     * Compiles a layer object schema File to a specific Object file
     * @param LayerObjectSchemaFile $schemaFile
     * @param NamespaceVO $namespace
     * @param File $file
     */
    protected function compileObjectSchemaToFile(
        LayerObjectSchemaFile $schemaFile,
        NamespaceVO $namespace,
        File $file
    )
    {
        $this->logger->info("Compiling schema $schemaFile ...");
        $this->objectCompiler->compileSchemaFileWithNamespaceToFile($schemaFile, $namespace, $file);
    }
}
