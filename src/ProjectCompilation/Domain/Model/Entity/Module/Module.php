<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\NamespaceVO;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project\Project;
use Morebec\ValueObjects\File\Path;
use Morebec\ValueObjects\Text\Description;

/**
 * A Module is a grouping of related code around a broad domain concept. 
 * E.g.: UserManagement, Payment, Security.
 * It is represented by a directory inside the 
 * *Project*'s *Source code directory*.
 */
final class Module
{
    /** @var Project Project containing this module */
    private $project;

    /** @var ModuleConfiguration Configuration of the module */
    private $configuration;

    /** @var AbstractLayer[] Layers of the module */
    private $layers;

    /**
     * @param ModuleConfiguration $configuration Configuration of the module
     */
    public function __construct(ModuleConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Returns the namespace of the Module
     * @return NamespaceVO
     */
    public function getNamespace(): NamespaceVO
    {
        $projectNamespace = $this->project->getNamespace();
        return new NamespaceVO($projectNamespace->appendString($this->getName()));
    }

    /**
     * Returns the value of project
     * @return Project Value of project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * Returns the value of name
     * @return string Value of name
     */
    public function getName(): string
    {
        return $this->configuration->getModuleName();
    }

    /**
     * Returns the value of configFile
     * @return ModuleConfiguration Value of configFile
     */
    public function getConfiguration(): ModuleConfiguration
    {
        return $this->configuration;
    }

    public function getConfigurationFile(): ModuleConfigurationFile
    {
        return $this->configuration->getConfigurationFile();
    }


    /**
     * Returns the value of layers
     * @return AbstractLayer[] Value of layers
     */
    public function getLayers(): array
    {
        return $this->layers;
    }


    /**
     * Sets the value of project
     * @param Project $project new value of project
     * @return
     */
    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    /**
     * Sets the value of configFile
     * @param ModuleConfiguration $configuration new value of configFile
     * @return
     */
    public function setConfiguration(ModuleConfiguration $configuration): void
    {
        $this->configuration = $configuration;
    }


    /**
     * Sets the value of layers
     * @param AbstractLayer[] $layers new value of layers
     * @return
     */
    public function setLayers(array $layers): void
    {
        $this->layers = $layers;
    }

    /**
     * Adds a layer to this module
     * @param AbstractLayer $layer
     */
    public function addLayer(AbstractLayer $layer): void
    {
        $this->layers[$layer->getName()] = $layer;
    }

    /**
     * Returns the directory of the module
     */
    public function getDirectory(): ModuleDirectory
    {
        $sourceDir = $this->project->getSourceDirectory();
        $path = $sourceDir . '/' . $this->getName();
        return new ModuleDirectory(new Path($path));
    }

    public function getDescription(): ?Description
    {
        return $this->configuration->getDescription();
    }

    public function getProjectConfiguration()
    {
        return $this->project->getConfiguration();
    }
}

