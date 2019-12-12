<?php

namespace Morebec\Orkestra\ProjectGeneration\Infrastructure\Loader;

use Assert\Assertion;
use Morebec\Orkestra\ProjectGeneration\Domain\Exception\InvalidModuleConfigurationException;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\ProjectConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\ProjectConfigurationFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Loader\ProjectConfigurationLoaderInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Project Configuration loader, loads the configuration of a project 
 * from a file on disk.
 */
class YamlProjectConfigurationLoader extends YamlFileLoader implements ProjectConfigurationLoaderInterface
{
    /**
     * @param ProjectConfigurationFile $configurationFile
     * @return ProjectConfiguration
     * @throws InvalidModuleConfigurationException
     */
    public function load(ProjectConfigurationFile $configurationFile): ProjectConfiguration
    {
        $data = $this->loadFile($configurationFile);

        if(!is_array($data)) {
            throw new InvalidModuleConfigurationException(
                "Invalid Module Configuration: It should start with the name of the module followed by its definition at $configurationFile"
            );
        }

        $conf = ProjectConfiguration::fromArray($configurationFile, $data);
        
        return $conf;
    }
}
