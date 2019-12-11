<?php

namespace Morebec\Orkestra\ProjectGeneration\Infrastructure\ConfigurationLoader;

use Assert\Assertion;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\ProjectConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\ProjectConfigurationFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Loader\ProjectConfigurationLoaderInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Project Configuration loader, loads the configuration of a project 
 * from a file on disk.
 */
class YamlProjectConfigurationLoader implements ProjectConfigurationLoaderInterface
{
    public function load(ProjectConfigurationFile $configurationFile): ProjectConfiguration
    {
        Assertion::file((string)$configurationFile);
        $data = Yaml::parse($configurationFile->getContent());
        $conf = ProjectConfiguration::fromArray($configurationFile, $data);
        
        return $conf;
    }
}
