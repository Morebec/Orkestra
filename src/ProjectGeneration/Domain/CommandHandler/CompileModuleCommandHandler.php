<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\CommandHandler;


use Morebec\Orkestra\ProjectGeneration\Domain\Command\CompileModuleCommand;
use Morebec\Orkestra\ProjectGeneration\Domain\Event\ModuleCompiledEvent;
use Morebec\Orkestra\ProjectGeneration\Domain\Exception\ProjectConfigurationFileNotFoundException;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\ProjectConfigurationFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Factory\ProjectFactory;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Compiler\ModuleCompiler;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Locator\ProjectConfigurationFileLocator;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Handler for the CompileModuleCommand
 */
class CompileModuleCommandHandler implements MessageHandlerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ProjectFactory
     */
    private $projectFactory;

    /**
     * @var ModuleCompiler
     */
    private $moduleCompiler;
    /**
     * @var ProjectConfigurationFileLocator
     */
    private $projectConfigurationFileLocator;

    public function __construct(
            ProjectFactory $projectFactory,
            ModuleCompiler $moduleCompiler,
            ProjectConfigurationFileLocator $projectConfigurationFileLocator,
            EventDispatcherInterface $eventDispatcher
    )
    {
        $this->projectFactory = $projectFactory;
        $this->moduleCompiler = $moduleCompiler;
        $this->eventDispatcher = $eventDispatcher;
        $this->projectConfigurationFileLocator = $projectConfigurationFileLocator;
    }
    
    public function __invoke(CompileModuleCommand $command)
    {
        $moduleName = $command->getModuleName();
        $projectConfigurationPath = $command->getProjectConfigPath();

        // Determine Project Configuration file to use, if it was not specified in the command,
        // We'll need to find it
        if(!$projectConfigurationPath) {
            $projectConfigFile = $this->projectConfigurationFileLocator->locate(new Directory(new Path(getcwd())));
        } else {
            $projectConfigFile = ProjectConfigurationFile::makeFromPath(new Path($projectConfigurationPath));
        }

        // Get project
        $project = $this->projectFactory->createFromFile($projectConfigFile);
        
        // Get Module
        $module = $project->getModuleWithName($moduleName);
        
        // Compile Module
        $this->moduleCompiler->compile($module);
        
        // Fire Event
        $this->eventDispatcher->dispatch(new ModuleCompiledEvent($module));
    }
}
