<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project;

use Assert\Assertion;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\ModuleNotFoundException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Composer\ComposerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\Module;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\NamespaceVO;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\File;

/**
 * Represents an Orkestra project.
 * An Orkestra project is a directory containing a Symfony project.
 * The main important pieces that an Orkestra Project needs 
 * are the following:
 * - A composer.json file with PSR4 loader definition
 * - PHP Source files directory
 * - PHP Test files directory
 * - A Module files directory
 * - An orkestra.yaml configuration file
 * 
 * With the above a project can be considered as such.
 * If any of these pieces are missing, the project is
 * considered incomplete/broken.
 * 
 * @Aggregate
 */
final class Project
{
    /**
     * @var ComposerConfiguration
     */
    private $composerConfiguration;

    /** @var ProjectConfiguration configuration of the project */
    private $configuration;

    /** @var Module[] List of modules of the project */
    private $modules;

    /**
     * @param ProjectConfiguration $projectConfiguration Configuration file of the project
     * @param ComposerConfiguration $composerConfiguration
     */
    public function __construct(
            ProjectConfiguration $projectConfiguration,
            ComposerConfiguration $composerConfiguration
    )
    {
        $this->modules = [];
        $this->configuration = $projectConfiguration;
        $this->composerConfiguration = $composerConfiguration;
    }

    /**
     * Returns the directory containing the project
     * @return Directory
     * @throws \Exception
     */
    public function getDirectory(): Directory
    {
        return $this->configuration->getProjectDirectory();
    }
    
    /**
     * Returns the project's composer configuration
     * @return ComposerConfiguration
     */
    public function getComposerConfiguration(): ComposerConfiguration
    {
        return $this->composerConfiguration;
    }

    /**
     * Returns the directory that contains the project's PHP source files
     * @return Directory
     */
    public function getSourceDirectory(): Directory
    {
        return $this->configuration->getSourceDirectory();
    }
    
    /**
     * Returns the directory that contains the project's PHP test files
     * @return Directory
     */
    public function getTestsDirectory(): Directory
    {
        return $this->configuration->getTestsDirectory();
    }
    
    /**
     * Returns the directory that contains the configurations files for modules
     * @return Directory
     */
    public function getModulesDirectory(): Directory
    {
        return $this->configuration->getModulesDirectory();
    }

    /**
     * Returns the Documentation directory
     * @return DocumentationDirectory
     */
    public function getDocumentationDirectory(): DocumentationDirectory
    {
        return $this->configuration->getDocumentationDirectory();
    }

    /**
     * Returns the value of modules
     * @return Module[] Value of modules
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    public function addModule(Module $module)
    {
        $this->modules[$module->getName()] = $module;
        $module->setProject($this);
    }

    public function getNamespace(): NamespaceVO
    {
        $namespaces = $this->composerConfiguration->getPsr4Namespaces();
        Assertion::minCount($namespaces, 1, 'No PSR4 namespace configured. Please configure it in composer.json');
        $key = array_key_first($namespaces);
        return $namespaces[$key];
    }

    public function getModuleWithName(string $moduleName)
    {
        if(!array_key_exists($moduleName, $this->modules)) {
            throw new ModuleNotFoundException($moduleName);
        }

        return $this->modules[$moduleName];
    }

    /**
     * @return ProjectConfigurationFile
     */
    public function getConfigurationFile(): ProjectConfigurationFile
    {
        return $this->configuration->getConfigurationFile();
    }

    /**
     * @return ProjectConfiguration
     */
    public function getConfiguration(): ProjectConfiguration
    {
        return $this->configuration;
    }
}

