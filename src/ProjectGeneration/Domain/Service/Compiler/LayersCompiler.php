<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Compiler;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Application\ApplicationLayer;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Application\ApplicationLayerConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Domain\DomainLayerConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Infrastructure\InfrastructureLayerConfiguration;
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
    /**
     * Array of compiler, where the key is the compiler name
     * and the value the compiler
     * @var AbstractLayerCompiler[]
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
        LayerObjectCompilerInterface $objectCompiler,
        LoggerInterface $logger
    )
    {
        $this->filesystem = new Filesystem();

        $this->compilers = [
            ApplicationLayerConfiguration::LAYER_NAME => new ApplicationAbstractLayerCompiler($objectCompiler, $logger),
            DomainLayerConfiguration::LAYER_NAME => new DomainAbstractLayerCompiler($objectCompiler, $logger),
            InfrastructureLayerConfiguration::LAYER_NAME => new InfrastructureAbstractLayerCompiler($objectCompiler, $logger)
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

        foreach($layer->getConfiguredSubDirectories() as $directory) {
            $this->createDirectory($directory);
        }
    }

    /**
     * Compiles a layer
     * @param AbstractLayer $layer
     */
    public function compileLayer(AbstractLayer $layer)
    {
        $this->logger->info(sprintf('Compiling layer %s ...', $layer->getName()));
        $compiler = $this->getCompilerForLayer($layer);
        $compiler->compile($layer);
    }

    private function getCompilerForLayer(
            AbstractLayer $layer
    ): AbstractLayerCompiler
    {
        return $this->compilers[$layer->getName()];
    }

    /**
     * Creates a directory, only if it does not exist
     * @param Directory $dir directory to create
     */
    private function createDirectory(Directory $dir) {
        // The filesystem::mkdir function does not throw errors
        // if the directory already exists
        $this->filesystem->mkdir((String)$dir);
    }
}
