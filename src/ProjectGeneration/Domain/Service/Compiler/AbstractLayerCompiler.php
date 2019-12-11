<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Compiler;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\AbstractLayer;
use Morebec\ValueObjects\File\Directory;
use Symfony\Component\Filesystem\Filesystem;

/**
 * A Layer compiler is responsible for compiling all the resources of a layer 
 * and creating the necessary directories to support them.
 */
abstract class AbstractLayerCompiler
{
    /**
     * @var Filesystem 
     */
    protected $filesystem;
    
    /**
     * @var ObjectGenerator 
     */
    protected $objectGenerator;
    
    public function __construct()
    {
        $this->filesystem = new Filesystem();
        $this->objectGenerator = new ObjectGenerator();
    }
    
    /**
     * Compiles a layer
     */
    public abstract function compiler(AbstractLayer $layer);
    
    /**
     * Creates the directories of a layer
     * @param AbstractLayer $layer
     */
    protected function createLayerDirectories(AbstractLayer $layer): void
    {
         // Create the layer's directory
        $this->createDirectory($layer->getDirectories());

        // Create all directories inside the layer's directory
        $layerSubDirectories = $layer->getDefaultSubDirectories();
        foreach ($layerSubDirectories as $directory) {
            $this->createDirectory($directory);
        }
    }
    
    /**
     * Creates a directory, only if it does not exist
     * @param Directory $dir directory to create
     */
    private function createDirectory(Directory $dir): void
    {
        // The filesystem::mkdir function does not throw errors
        // if the directory already exists
        $this->filesystem->mkdir((String) $dir);
    }
}
