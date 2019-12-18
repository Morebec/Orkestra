<?php

namespace Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader;

use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidModuleConfigurationException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfigurationFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader\ModuleConfigurationLoaderInterface;

class YamlModuleConfigurationLoader extends YamlFileLoader implements ModuleConfigurationLoaderInterface
{

    /**
     * @inheritDoc
     */
    public function load(ModuleConfigurationFile $configurationFile): ModuleConfiguration
    {
        $data = $this->loadFile($configurationFile);

        if (!is_array($data)) {
            throw new InvalidModuleConfigurationException(
                "Invalid Module Configuration: It should start with the name of the module followed by its definition at $configurationFile"
            );
        }

        $conf = ModuleConfiguration::fromArray($configurationFile, $data);

        return $conf;
    }
}
