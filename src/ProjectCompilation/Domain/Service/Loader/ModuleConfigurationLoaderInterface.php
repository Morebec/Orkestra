<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfigurationFile;

/**
 * Interface for module configuration loader, responsible for loading module configuration from file
 */
interface ModuleConfigurationLoaderInterface
{
    /**
     * Loads a module configuration from a configuration file
     * @param ModuleConfigurationFile $configurationFile
     * @return ModuleConfiguration
     */
    public function load(ModuleConfigurationFile $configurationFile): ModuleConfiguration;
}