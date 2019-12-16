<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\Module;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\NamespaceVO;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project\ProjectConfiguration;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;

/**
 * Represents a Layer in a Module
 */
abstract class AbstractLayer
{
    /** @var
     * string name of the layer
     */
    protected $name;

    /** @var
     * Module Module of the layer
     */
    private $module;

    /**
     * @var AbstractLayerConfiguration
     */
    private $configuration;

    /**
     * @param Module $module Module of the layer
     * @param AbstractLayerConfiguration $configuration
     */
    public function __construct(Module $module, AbstractLayerConfiguration $configuration)
    {
        $this->module = $module;
        $this->configuration = $configuration;
    }

    /**
     * Returns the value of name
     * @return string Value of name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the value of module
     * @return Module Value of module
     */
    public function getModule(): Module
    {
        return $this->module;
    }
    
    /**
     * Returns the directory of the layer
     * @return Directory
     */
    public function getDirectory(): Directory
    {
        $path = $this->module->getDirectory() . '/' . $this->getName();
        return new Directory(new Path($path));
    }
    
    /**
     * Returns the configured subdirectories of this layer
     * @return Directory[]
     */
    public function getConfiguredSubDirectories(): array
    {
        $names = $this->configuration->getSubDirectoryNames();
        $layerDirectory = $this->getDirectory();
        return array_map(static function(string $name) use ($layerDirectory) {
            return new Directory(new Path($layerDirectory . '/' . $name));
        }, $names);
    }
    
    /**
     * Returns the namespace of this layer
     * @return NamespaceVO
     */
    public function getNamespace(): NamespaceVO
    {
        return $this->module->getNamespace()->appendString($this->getName());
    }

    /**
     * Returns the configuration of this layer
     * @return AbstractLayerConfiguration
     */
    public function getConfiguration(): AbstractLayerConfiguration
    {
        return $this->configuration;
    }

    /**
     * @return ProjectConfiguration
     */
    public function getProjectConfiguration(): ProjectConfiguration
    {
        return $this->module->getProjectConfiguration();
    }
}

