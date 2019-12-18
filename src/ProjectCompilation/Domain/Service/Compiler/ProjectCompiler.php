<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Compiler;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project\Project;
use Morebec\ValueObjects\File\Directory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Compiler for Project resources
 */
class ProjectCompiler
{
    /**
     * @var ModuleCompiler
     */
    private $moduleCompiler;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(ModuleCompiler $moduleCompiler, LoggerInterface $logger)
    {
        $this->filesystem = new Filesystem();
        $this->moduleCompiler = $moduleCompiler;
        $this->logger = $logger;
    }

    /**
     * Compiles a project
     * @param Project $project
     */
    public function compile(Project $project): void
    {
        $this->logger->info(PHP_EOL . 'Compiling Project ...' . PHP_EOL);
        // Create directories
        $this->createDirectory($project->getModulesDirectory());
        $this->createDirectory($project->getSourceDirectory());
        $this->createDirectory($project->getTestsDirectory());
        $this->createDirectory($project->getDocumentationDirectory());

        // Compile modules
        foreach ($project->getModules() as $module) {
            $this->moduleCompiler->compile($module);
        }
    }

    /**
     * Creates a directory
     * @param Directory $dir
     */
    private function createDirectory(Directory $dir): void
    {
        $this->filesystem->mkdir((string)$dir);
    }

    public function cleanProject(Project $project)
    {
        $this->logger->info(PHP_EOL . 'Cleaning Project ...' . PHP_EOL);
        foreach ($project->getModules() as $module) {
            $this->moduleCompiler->cleanModule($module);
        }
    }
}
