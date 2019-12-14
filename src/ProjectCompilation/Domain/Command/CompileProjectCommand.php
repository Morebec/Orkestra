<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Command;

/**
 * CommandObject use to compile a project.
 * It uses its Orkestra configuration file path
 */
class CompileProjectCommand
{
    /**
     * @var string|null
     */
    private $projectConfigPath;

    public function __construct(?string $projectConfigPath = null)
    {
        $this->projectConfigPath = $projectConfigPath;
    }

    function getProjectConfigPath(): ?string
    {
        return $this->projectConfigPath;
    }
}
