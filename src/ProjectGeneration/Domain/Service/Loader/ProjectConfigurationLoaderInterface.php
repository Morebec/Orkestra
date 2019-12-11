<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Loader;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\ProjectConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\ProjectConfigurationFile;

/**
 * Interface for Project configuration loader
 */
interface ProjectConfigurationLoaderInterface
{
    /**
     * Loads a Project Configuration from a ProjectConfigurationFile
     * @param ProjectConfigurationFile $configurationFile
     * @return ProjectConfiguration
     */
    public function load(ProjectConfigurationFile $configurationFile): ProjectConfiguration;
}
