<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\AbstractLayerConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Domain\DomainLayerConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Application\ApplicationLayerConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Infrastructure\InfrastructureLayerConfiguration;
use Morebec\ValueObjects\Text\Description;

/**
 * ModuleConfiguration
 */
class ModuleConfiguration
{
    const DESCRIPTION_KEY = 'desc';

    /**
     * Name of the module
     * @var string
     */
    private $moduleName;

    /**
     * @var Description|null
     */
    private $description;

    /**
     * @var AbstractLayerConfiguration[]
     */
    private $layerConfigurations;
    /**
     * @var ModuleConfigurationFile
     */
    private $configurationFile;

    /**
     * Creates a module configuration instance from an array representation
     * @param ModuleConfigurationFile $configurationFile
     * @param $data
     * @return ModuleConfiguration
     */
    public static function fromArray(ModuleConfigurationFile $configurationFile, array $data): ModuleConfiguration
    {
        $moduleName = array_key_first($data);
        $data = $data[$moduleName];

        $mc = new static($moduleName, $configurationFile);

        $mc->setDescription(
            array_key_exists(self::DESCRIPTION_KEY,  $data) ?
                new Description($data[self::DESCRIPTION_KEY]) : null
        );

        $mc->addLayerConfiguration(ApplicationLayerConfiguration::fromArray(
            $data[ApplicationLayerConfiguration::LAYER_NAME])
        );

        $mc->addLayerConfiguration(DomainLayerConfiguration::fromArray(
            $data[DomainLayerConfiguration::LAYER_NAME])
        );

        $mc->addLayerConfiguration(InfrastructureLayerConfiguration::fromArray(
            $data[InfrastructureLayerConfiguration::LAYER_NAME])
        );

        return $mc;
    }

    private function __construct(string $moduleName, ModuleConfigurationFile $configurationFile)
    {
        $this->moduleName = $moduleName;
        $this->layerConfigurations = [];
        $this->configurationFile = $configurationFile;
    }

    /**
     * Returns the name of the module
     * @return string
     */
    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    /**
     * Returns the description of the module
     * @return Description|null
     */
    public function getDescription(): ?Description
    {
        return $this->description;
    }

    /**
     * Returns the module's configuration file
     * @return ModuleConfigurationFile
     */
    public function getConfigurationFile(): ModuleConfigurationFile
    {
        return $this->configurationFile;
    }

    /**
     * Sets the description
     * @param Description $description
     */
    private function setDescription(?Description $description): void
    {
        $this->description = $description;
    }

    public function addLayerConfiguration(AbstractLayerConfiguration $layerConfiguration): void
    {
        $this->layerConfigurations[$layerConfiguration->getName()] = $layerConfiguration;
    }

    public function getLayerConfigurations(): array
    {
        return $this->layerConfigurations;
    }
}
