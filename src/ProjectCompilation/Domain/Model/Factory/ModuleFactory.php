<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Factory;


use Morebec\Orkestra\ProjectCompilation\Domain\Exception\UnsupportedModuleLayerException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Application\ApplicationLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Application\ApplicationLayerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Domain\DomainLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Domain\DomainLayerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Infrastructure\InfrastructureLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Infrastructure\InfrastructureLayerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\Module;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfigurationFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader\ModuleConfigurationLoaderInterface;

class ModuleFactory
{
    /**
     * @var ModuleConfigurationLoaderInterface
     */
    private $configurationLoader;

    public function __construct(ModuleConfigurationLoaderInterface $configurationLoader)
    {
        $this->configurationLoader = $configurationLoader;
    }

    /**
     * Creates a Module from a configuration file
     * @param ModuleConfigurationFile $configurationFile
     * @return Module
     * @throws UnsupportedModuleLayerException
     */
    public function createFromModuleConfigurationFile(ModuleConfigurationFile $configurationFile): Module
    {
        $configuration = $this->configurationLoader->load($configurationFile);

        return $this->createFromModuleConfiguration($configuration);
    }

    /**
     * Creates a module from a configuration
     * @param ModuleConfiguration $configuration
     * @return Module
     * @throws UnsupportedModuleLayerException
     */
    private function createFromModuleConfiguration(ModuleConfiguration $configuration): Module
    {
        $module = new Module($configuration);

        foreach($configuration->getLayerConfigurations() as $layerConfiguration) {
            $layer = null;
            $layerName = $layerConfiguration->getName();

            if($layerName === ApplicationLayerConfiguration::LAYER_NAME) {
                $layer = new ApplicationLayer($module, $layerConfiguration);

            } elseif($layerName === DomainLayerConfiguration::LAYER_NAME) {
                $layer = new DomainLayer($module, $layerConfiguration);

            } elseif($layerName === InfrastructureLayerConfiguration::LAYER_NAME) {
                $layer = new InfrastructureLayer($module, $layerConfiguration);
            }

            if(!$layer) {
                throw new UnsupportedModuleLayerException($configuration->getConfigurationFile(), $layerName);
            }

            $module->addLayer($layer);
        }

        return $module;
    }
}