<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Compiler;


use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module\Module;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;
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

    public function __construct(
            Filesystem $filesystem,
            LayersCompiler $layersCompiler
    )
    {
        $this->filesystem = $filesystem;
        $this->layersCompiler = $layersCompiler;
    }
    
    public function compile(Module $module)
    {
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
        foreach ($layers as $layer) {
            $this->createLayerDirectories($layer);
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
     * Creates a directory, only if it does not exist
     * @param Directory $dir directory to create
     */
    private function createDirectory(Directory $dir) {
        // The filesystem::mkdir function does not throw errors
        // if the directory already exists
        $this->filesystem->mkdir((String)$dir);
    }


}
