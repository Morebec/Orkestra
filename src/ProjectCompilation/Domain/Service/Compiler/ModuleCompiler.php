<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Compiler;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\Module;
use Morebec\ValueObjects\File\Directory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Responsible for compiling modules
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
    ) {
        $this->filesystem = $filesystem;
        $this->layersCompiler = $layersCompiler;
        $this->logger = $logger;
    }

    /**
     * Compiles the module and its layers and objects
     * @param Module $module
     */
    public function compile(Module $module)
    {
        $this->logger->info(PHP_EOL . "Compiling module {$module->getName()} ..." . PHP_EOL);
        // Create the module's directory
        $this->createDirectory($module->getDirectory());

        // Create Documentation
        $this->createDocumentation($module);

        // Compiler Layers
        $this->compilerLayers($module);
    }

    /**
     * Cleans the modules directory from compiled Layer Objects
     * @param Module $module
     */
    public function cleanModule(Module $module): void
    {
        $this->logger->info(PHP_EOL . "Cleaning {$module->getName()}" . PHP_EOL);
        foreach ($module->getLayers() as $layer) {
            $dir = $layer->getDirectory();
            if(!$dir->exists()) {
                continue;
            }

            // TODO ACL
            $files = Finder::create()->in((string)$dir)
                                     ->files()
                                     ->name('*.php')
                                     ->contains('@Orkestra\Generated');
            /** @var SplFileInfo $file */
            foreach ($files as $file) {
                $pathname = $file->getPathname();
                $this->logger->info("Deleting {$pathname} ...");
                unlink($pathname);
            }
        }
    }

    /**
     * Creates the documentation of the module
     * @param Module $module
     */
    private function createDocumentation(Module $module)
    {
        $description = $module->getDescription();
        if (!$description) {
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
    private function createDirectory(Directory $dir)
    {
        // The filesystem::mkdir function does not throw errors
        // if the directory already exists
        $this->filesystem->mkdir((String)$dir);
    }
}
