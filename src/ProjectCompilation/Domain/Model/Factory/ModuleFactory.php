<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Factory;

use Morebec\FileLocator\FileLocator;
use Morebec\FileLocator\FileLocatorStrategy;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\UnsupportedModuleLayerException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Application\ApplicationLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Application\ApplicationLayerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Domain\DomainLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Domain\DomainLayerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Generic\GenericLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Infrastructure\InfrastructureLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Infrastructure\InfrastructureLayerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\Module;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfigurationFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\UseCase\UseCase;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\UseCase\UseCaseConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\UseCase\UseCaseConfigurationFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader\ModuleConfigurationLoaderInterface;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader\UseCaseConfigurationDataLoaderInterface;
use Morebec\ValueObjects\File\Path;

class ModuleFactory
{
    /**
     * @var ModuleConfigurationLoaderInterface
     */
    private $configurationLoader;
    /**
     * @var UseCaseConfigurationDataLoaderInterface
     */
    private $useCaseConfigurationLoader;

    public function __construct(
        ModuleConfigurationLoaderInterface $configurationLoader,
        UseCaseConfigurationDataLoaderInterface $useCaseConfigurationLoader
    ) {
        $this->configurationLoader = $configurationLoader;
        $this->useCaseConfigurationLoader = $useCaseConfigurationLoader;
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


        /** @var AbstractLayerConfiguration $layerConfiguration */
        foreach ($configuration->getLayerConfigurations() as $layerConfiguration) {
            $layer = null;
            $layerName = $layerConfiguration->getName();

            if ($layerName === ApplicationLayerConfiguration::LAYER_NAME) {
                $layer = new ApplicationLayer($module, $layerConfiguration);
            } elseif ($layerName === DomainLayerConfiguration::LAYER_NAME) {
                $layer = new DomainLayer($module, $layerConfiguration);
            } elseif ($layerName === InfrastructureLayerConfiguration::LAYER_NAME) {
                $layer = new InfrastructureLayer($module, $layerConfiguration);
            } else {
                $layer = new GenericLayer($module, $layerConfiguration);
            }

            $module->addLayer($layer);
        }

        // Load use cases and dispatch their objects into the right layers
        // that we'll need to pass to layer configurations
        $fileLocator = new FileLocator();
        $useCaseConfigurationFiles = $fileLocator->findAll(
            UseCaseConfigurationFile::BASENAME,
            $configuration->getDirectory(),
            FileLocatorStrategy::RECURSIVE_DOWN()
        );

        foreach ($useCaseConfigurationFiles as $useCaseConfigurationFile) {
            $useCaseConfigurationFile = new UseCaseConfigurationFile(new Path($useCaseConfigurationFile));
            $data = $this->useCaseConfigurationLoader->loadDataFromFile($useCaseConfigurationFile);
            $useCaseConfig = UseCaseConfiguration::fromArray($useCaseConfigurationFile, $data);
            $useCase = new UseCase($module, $useCaseConfig);
            $module->addUseCase($useCase);

            foreach ($useCaseConfig->getObjectConfigurations() as $layerName => $objectsKey) {
                $layer = $module->getLayerByName($layerName);
                $layerConfiguration = $layer->getConfiguration();
                foreach ($objectsKey as $key => $objectConfigs) {
                    foreach($objectConfigs as $objectConfig) {
                        $layerConfiguration->addModuleObjectConfiguration($key, $objectConfig);
                    }
                }
            }
        }

        return $module;
    }
}
