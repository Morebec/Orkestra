<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Compiler;


use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\Module;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Responsible for compiling mdules
 */
class ModuleCompiler
{
    /**
     * @var LayersCompiler
     */
    private $layersCompiler;

    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
            Filesystem $filesystem,
            LayersCompiler $layersCompiler,
            LoggerInterface $logger
    )
    {
        $this->filesystem = $filesystem;
        $this->layersCompiler = $layersCompiler;
        $this->logger = $logger;
    }
    
    public function compile(Module $module)
    {
        $this->logger->info(sprintf('Compiling module %s ...', $module->getName()));
        // Create the module's directory
        $this->createDirectory($module->getDirectory());

        // Create Documentation
        $this->createDocumentation($module);

        // Compiler Layers
        $this->compilerLayers($module);
    }

    /**
     * Creates the documentation of the module
     * @param Module $module
     */
    private function createDocumentation(Module $module)
    {
        $description = $module->getDescription();
        if(!$description) {
            return;
        }

        file_put_contents($module->getDirectory() . '/README.md', (string)$description);
    }

    /**
     * Compiles the different layers of the module
     * @param Module $module
     */
    private function compilerLayers(Module $module)
    {
        // Create the layers' directories
        $layers = $module->getLayers();
        $this->layersCompiler->compileLayers($layers);
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
