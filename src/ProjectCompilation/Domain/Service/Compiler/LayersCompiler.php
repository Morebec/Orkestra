<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Compiler;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Application\ApplicationLayerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Domain\DomainLayerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Infrastructure\InfrastructureLayerConfiguration;
use Morebec\ValueObjects\File\Directory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Compiler for layers.
 * This serves more as a dispatcher for the specific LayerCompilers.
 * Every layer type has its own compiler
 */
class LayersCompiler
{
    const GENERIC_LAYER_NAME = '__generic_layer_name__';
    /**
     * Array of compiler, where the key is the compiler name
     * and the value the compiler
     * @var LayerCompiler[]
     */
    private $compilers;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        ApplicationLayerCompiler $applicationLayerCompiler,
        DomainLayerCompiler $domainLayerCompiler,
        InfrastructureLayerCompiler $infrastructureLayerCompiler,
        GenericLayerCompiler $genericLayerCompiler,
        LoggerInterface $logger
    ) {
        $this->filesystem = new Filesystem();

        $this->compilers = [
            ApplicationLayerConfiguration::LAYER_NAME => $applicationLayerCompiler,
            DomainLayerConfiguration::LAYER_NAME => $domainLayerCompiler,
            InfrastructureLayerConfiguration::LAYER_NAME => $infrastructureLayerCompiler,
            self::GENERIC_LAYER_NAME => $genericLayerCompiler
        ];

        $this->logger = $logger;
    }

    /**
     * Compiles a set of layers
     * @param AbstractLayer[] $layers
     */
    public function compileLayers(array $layers)
    {
        foreach ($layers as $layer) {
            $this->createLayerDirectories($layer);
            $this->compileLayer($layer);
        }
    }

    /**
     * Creates the layer's subdirectories
     * @param AbstractLayer $layer
     */
    private function createLayerDirectories(AbstractLayer $layer)
    {
        $layerDirectory = $layer->getDirectory();
        $this->createDirectory($layerDirectory);

        foreach ($layer->getConfiguredSubDirectories() as $directory) {
            $this->createDirectory($directory);
        }
    }

    /**
     * Compiles a layer
     * @param AbstractLayer $layer
     */
    public function compileLayer(AbstractLayer $layer)
    {
        $this->logger->info(PHP_EOL . "Compiling layer {$layer->getName()} ..." . PHP_EOL);
        $compiler = $this->getCompilerForLayer($layer);
        $compiler->compile($layer);
    }

    private function getCompilerForLayer(
        AbstractLayer $layer
    ): LayerCompiler {
        $layerName = $layer->getName();

        if (!array_key_exists($layerName, $this->compilers)) {
            $layerName = self::GENERIC_LAYER_NAME;
        }

        return $this->compilers[$layerName];
    }

    /**
     * Creates a directory, only if it does not exist
     * @param Directory $dir directory to create
     */
    private function createDirectory(Directory $dir)
    {
        // The filesystem::mkdir function does not throw errors
        // if the directory already exists
        $this->filesystem->mkdir((String)$dir);
    }
}
