<?php


namespace Morebec\Orkestra\ProjectGeneration\Application\Shared\Service;

use Morebec\Orkestra\ProjectGeneration\Domain\Command\CompileModuleCommand;
use Morebec\Orkestra\ProjectGeneration\Domain\Command\CompileProjectCommand;
use Morebec\Orkestra\ProjectGeneration\Domain\Exception\ProjectConfigurationFileNotFoundException;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module\Module;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\Project;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\ProjectProvider;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Access point for applications
 */
class ApplicationService
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var ProjectProvider
     */
    private $projectProvider;

    public function __construct(
        MessageBusInterface $messageBus,
        ProjectProvider $projectProvider
    )
    {
        $this->messageBus = $messageBus;
        $this->projectProvider = $projectProvider;
    }

    /**
     * Compiles a module by its name
     * @param string $moduleName
     * @param string|null $projectConfigurationFilePath
     */
    public function compileModule(string $moduleName, ?string $projectConfigurationFilePath = null): void
    {
        $this->messageBus->dispatch(new CompileModuleCommand($moduleName, $projectConfigurationFilePath));
    }

    /**
     * Compiles a module by its name
     * @param string|null $projectConfigurationFilePath
     */
    public function compileProject(?string $projectConfigurationFilePath = null): void
    {
        $this->messageBus->dispatch(new CompileProjectCommand($projectConfigurationFilePath));
    }

    /**
     * Returns the list of project modules
     * @param string|null $projectConfigurationFilePath
     * @return Module[]
     * @throws ProjectConfigurationFileNotFoundException
     */
    public function listModules(?string $projectConfigurationFilePath = null): array
    {
        $project = $this->projectProvider->getProject($projectConfigurationFilePath);
        return $project->getModules();
    }

    /**
     * Returns a project
     * @param string|null $projectConfigFilePath
     * @return Project
     * @throws ProjectConfigurationFileNotFoundException
     */
    public function getProject(?string $projectConfigFilePath): Project
    {
        return $this->projectProvider->getProject($projectConfigFilePath);
    }
}