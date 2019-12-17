<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Command;

/**
 * Command to clean the project from compiled layer objects
 */
class CleanProjectCommand
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