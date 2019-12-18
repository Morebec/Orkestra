<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\UseCase;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\Module;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project\ProjectConfiguration;

class UseCase
{
    /** @var UseCaseConfiguration */
    private $configuration;

    /** @var Module */
    private $module;

    /**
     * UseCase constructor.
     * @param Module $module
     * @param UseCaseConfiguration $configuration
     */
    public function __construct(Module $module, UseCaseConfiguration $configuration)
    {
        $this->module = $module;
        $this->configuration = $configuration;
    }

    public function getName(): string
    {
        return $this->configuration->getName();
    }

    public function getConfigurationFile(): UseCaseConfigurationFile
    {
        return $this->configuration->getConfigurationFile();
    }

    public function getProjectConfiguration(): ProjectConfiguration
    {
        return $this->module->getProjectConfiguration();
    }

    /**
     * @return UseCaseConfiguration
     */
    public function getConfiguration(): UseCaseConfiguration
    {
        return $this->configuration;
    }

    /**
     * @return UseCaseObjectConfiguration[]
     */
    public function getObjectConfigurations(): array
    {
        return $this->configuration->getObjectConfigurations();
    }

    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }
}
