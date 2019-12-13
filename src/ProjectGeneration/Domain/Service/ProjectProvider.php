<?php


namespace Morebec\Orkestra\ProjectGeneration\Domain\Service;

use Morebec\Orkestra\ProjectGeneration\Domain\Exception\ProjectConfigurationFileNotFoundException;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\Project;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\ProjectConfigurationFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Factory\ProjectFactory;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Locator\ProjectConfigurationFileLocator;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;

/**
 * Project provider provides a Project instance
 * by using the factory.
 * It supports two cases:
 *  - When the path to the Project Configuration File is known
 *  - When the path to the Project Configuration File is not known
 */
class ProjectProvider
{
    /**
     * @var ProjectFactory
     */
    private $projectFactory;

    /**
     * @var ProjectConfigurationFileLocator
     */
    private $projectConfigurationFileLocator;

    public function __construct(
        ProjectFactory $projectFactory,
        ProjectConfigurationFileLocator $projectConfigurationFileLocator
    )
    {
        $this->projectFactory = $projectFactory;
        $this->projectConfigurationFileLocator = $projectConfigurationFileLocator;
    }

    /**
     * @param string|null $projectConfigurationFilePath
     * @return Project
     * @throws ProjectConfigurationFileNotFoundException
     */
    public function findProject(?string $projectConfigurationFilePath): Project
    {
        $projectConfigFile = $this->findProjectConfigurationFile($projectConfigurationFilePath);

        if(!$projectConfigFile) {
            throw new ProjectConfigurationFileNotFoundException(new Path(getcwd()));
        }

        return $this->projectFactory->createFromFile($projectConfigFile);
    }

    /**
     * @param string|null $projectConfigurationFilePath
     * @return ProjectConfigurationFile|null
     */
    public function findProjectConfigurationFile(?string $projectConfigurationFilePath)
    {
        // Determine Project Configuration file to use, if it was not specified in the command,
        // We'll need to find it
        if (!$projectConfigurationFilePath) {
            $projectConfigFile = $this->projectConfigurationFileLocator->locate(new Directory(new Path(getcwd())));
        } else {
            $projectConfigFile = new ProjectConfigurationFile(new Path($projectConfigurationFilePath));
        }
        return $projectConfigFile;
    }
}