<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Command;

/**
 * Command used to clean a project module
 * from its compiled layer objects
 */
class CleanModuleCommand
{
    /**
     * Project Configuration File path
     * @var string|null
     */
    private $projectConfigPath;

    /**
     * Name of the module
     * @var string
     */
    private $moduleName;

    public function __construct(string $moduleName, ?string $projectConfigPath = null)
    {
        $this->moduleName = $moduleName;
        $this->projectConfigPath = $projectConfigPath;
    }
    
    function getProjectConfigPath(): ?string
    {
        return $this->projectConfigPath;
    }

    function getModuleName(): string
    {
        return $this->moduleName;
    }
}
