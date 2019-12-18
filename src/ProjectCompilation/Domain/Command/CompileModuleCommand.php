<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Command;

/**
 * CommandObject used to compile a project module
 */
class CompileModuleCommand
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
    
    public function getProjectConfigPath(): ?string
    {
        return $this->projectConfigPath;
    }

    public function getModuleName(): string
    {
        return $this->moduleName;
    }
}
