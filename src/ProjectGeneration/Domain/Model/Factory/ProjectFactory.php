<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Factory;


use Morebec\Orkestra\ProjectGeneration\Domain\Exception\ProjectConfigurationFileNotFoundException;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\Project;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\ProjectConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\ProjectConfigurationFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Loader\ComposerConfigurationLoaderInterface;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Loader\ProjectConfigurationLoaderInterface;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Locator\ComposerConfigurationFileLocator;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Locator\ModuleConfigurationFilesLocator;


/**
 * Factory to instantiate project objects
 * from their configuration or configuration file
 */
class ProjectFactory
{
    /**
     * @var ComposerConfigurationFileLocator
     */
    private $composerConfigurationFileLocator;

    /**
     * @var ProjectConfigurationLoaderInterface
     */
    private $projectConfigurationLoader;

    /**
     * @var ComposerConfigurationLoaderInterface
     */
    private $composerLoader;
    /**
     * @var ModuleFactory
     */
    private $moduleFactory;
    /**
     * @var ModuleConfigurationFilesLocator
     */
    private $modulesLocator;

    public function __construct(
            ComposerConfigurationLoaderInterface $composerConfigurationLoader,
            ComposerConfigurationFileLocator $composerConfigurationFileLocator,
            ProjectConfigurationLoaderInterface $projectConfigurationLoader,
            ModuleFactory $moduleFactory,
            ModuleConfigurationFilesLocator $modulesLocator
    )
    {
        $this->composerLoader = $composerConfigurationLoader;
        $this->composerConfigurationFileLocator = $composerConfigurationFileLocator;
        $this->projectConfigurationLoader = $projectConfigurationLoader;
        $this->moduleFactory = $moduleFactory;
        $this->modulesLocator = $modulesLocator;
    }

    /**
     * Creates a Project instance from a Project configuration file
     * @param ProjectConfigurationFile $configurationFile
     * @return Project
     * @throws ProjectConfigurationFileNotFoundException
     * @throws \Exception
     */
    public function createFromFile(ProjectConfigurationFile $configurationFile): Project
    {
        if(!$configurationFile->exists()) {
            throw new ProjectConfigurationFileNotFoundException(
                    $configurationFile->getRealPath()
            );
        }
        $configuration = $this->projectConfigurationLoader->load($configurationFile);
        $project = $this->createFromConfiguration($configuration);
       
        return $project;
    }

    /**
     * Creates a new Project instance from a Project Configuration
     * @param ProjectConfiguration $projectConfiguration
     * @return Project
     * @throws \Exception
     */
    private function createFromConfiguration(
            ProjectConfiguration $projectConfiguration
    ): Project
    {
        // Load composer for project
        $composer = $this->composerConfigurationFileLocator->locate(
            $projectConfiguration->getProjectDirectory()
        );
        
        $composerConfiguration = $this->composerLoader->load($composer);
        
        $project = new Project($projectConfiguration, $composerConfiguration);
        
        
        // Load modules
        $moduleConfigurationFiles = $this->modulesLocator->locate($projectConfiguration->getModulesDirectory());

        foreach($moduleConfigurationFiles as $moduleFile) {
            $module = $this->moduleFactory->createFromModuleConfigurationFile($moduleFile);
            $project->addModule($module);
        }

        return $project;
    }
}
