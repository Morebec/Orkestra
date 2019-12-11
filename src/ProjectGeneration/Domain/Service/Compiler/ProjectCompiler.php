<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Compiler;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\Project;
use Morebec\ValueObjects\File\Directory;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Compiler for Project resources
 */
class ProjectCompiler
{
    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * Compiles a project
     * @param Project $project
     */
    public function compile(Project $project): void
    {
        // Create directories
        $this->createDirectory($project->getModulesDirectory());
        $this->createDirectory($project->getSourceDirectory());
        $this->createDirectory($project->getTestsDirectory());
        $this->createDirectory($project->getDocumentationDirectory());
    }

    /**
     * Creates a directory
     * @param Directory $dir
     */
    private function createDirectory(Directory $dir): void
    {
        $this->filesystem->mkdir((string)$dir);
    }
}