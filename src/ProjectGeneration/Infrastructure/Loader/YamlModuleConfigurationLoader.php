<?php

namespace Morebec\Orkestra\ProjectGeneration\Infrastructure\Loader;

use Assert\Assertion;
use Morebec\Orkestra\ProjectGeneration\Domain\Exception\InvalidModuleConfigurationException;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module\ModuleConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module\ModuleConfigurationFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Loader\ModuleConfigurationLoaderInterface;
use Symfony\Component\Yaml\Yaml;

class YamlModuleConfigurationLoader extends YamlFileLoader implements ModuleConfigurationLoaderInterface
{

    /**
     * @inheritDoc
     */
    public function load(ModuleConfigurationFile $configurationFile): ModuleConfiguration
    {
        $data = $this->loadFile($configurationFile);

        if(!is_array($data)) {
            throw new InvalidModuleConfigurationException(
                "Invalid Module Configuration: It should start with the name of the module followed by its definition at $configurationFile"
            );
        }

        $conf = ModuleConfiguration::fromArray($configurationFile, $data);

        return $conf;
    }
}