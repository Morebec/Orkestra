<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\AbstractModuleObjectConfiguration;
use Morebec\ValueObjects\Text\Description;

class AbstractLayerConfiguration
{
    const DESCRIPTION_KEY = 'desc';

    /**
     * @var string[]
     */
    private $subDirectoryNames;

    /**
     * Description of the layer
     * @var Description|null
     */
    private $description;

    /**
     * Name of the layer
     * @var string
     */
    private $name;

    /**
     * @var array of array where key is section configuration key and value is LayerObjectConfiguration[]
     */
    private $objectConfigurations;

    /**
     * AbstractLayerConfiguration constructor.
     * @param string $name
     * @param string[] $subDirectoryNames
     * @param Description|null $description
     */
    public function __construct(string $name, array $subDirectoryNames, ?Description $description)
    {
        $this->subDirectoryNames = $subDirectoryNames;
        $this->description = $description;
        $this->name = $name;
        $this->objectConfigurations = [];
    }

    /**
     * @return string[]
     */
    public function getSubDirectoryNames(): array
    {
        return $this->subDirectoryNames;
    }

    /**
     * @return Description|null
     */
    public function getDescription(): ?Description
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Adds a new Module Object Configuration to this layer
     * @param string $key corresponds to one of the config section keys of the layer's
     *                                                configuration
     * @param AbstractModuleObjectConfiguration $configuration the object configuration to add
     */
    public function addModuleObjectConfiguration(string $key, AbstractModuleObjectConfiguration $configuration)
    {
        $this->objectConfigurations[$key][] = $configuration;
    }

    /**
     * Returns all the layer object configuration
     * @return array
     */
    public function getLayerObjectConfigurations(): array
    {
        return $this->objectConfigurations;
    }

    /**
     * Returns all the layer object configuration of a certain key
     * @param string $key                             corresponds to one of the config section keys of the layer's
     * @return LayerObjectConfiguration[]
     */
    public function getLayerObjectConfigurationsByKey(string $key): array
    {
        return $this->objectConfigurations[$key];
    }
}
