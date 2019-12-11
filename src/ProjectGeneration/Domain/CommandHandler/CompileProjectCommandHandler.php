<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\CommandHandler;

use Morebec\Orkestra\ProjectGeneration\Domain\Command\CompileProjectCommand;
use Morebec\Orkestra\ProjectGeneration\Domain\Exception\ProjectConfigurationFileNotFoundException;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\ProjectConfigurationFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Factory\ProjectFactory;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Compiler\ProjectCompiler;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Locator\ProjectConfigurationFileLocator;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CompileProjectCommandHandler implements MessageHandlerInterface
{
    /**
     * @var ProjectCompiler
     */
    private $projectCompiler;

    /**
     * @var ProjectConfigurationFileLocator
     */
    private $projectConfigurationFileLocator;

    /**
     * @var ProjectFactory
     */
    private $projectFactory;

    public function __construct(
        ProjectConfigurationFileLocator $projectConfigurationFileLocator,
        ProjectFactory $projectFactory,
        ProjectCompiler $projectCompiler
    )
    {
        $this->projectConfigurationFileLocator = $projectConfigurationFileLocator;
        $this->projectCompiler = $projectCompiler;
        $this->projectFactory = $projectFactory;
    }

    public function __invoke(CompileProjectCommand $command)
    {
        $projectConfigurationPath = $command->getProjectConfigPath();

        // Determine Project Configuration file to use, if it was not specified in the command,
        // We'll need to find it
        if(!$projectConfigurationPath) {
            $projectConfigFile = $this->projectConfigurationFileLocator->locate(new Directory(new Path(getcwd())));
        } else {
            $projectConfigFile = new ProjectConfigurationFile(new Path($projectConfigurationPath));
        }


        // Make sure project exists
        if(!$projectConfigFile->exists()) {
            throw new ProjectConfigurationFileNotFoundException(
                $projectConfigFile->getRealPath()
            );
        }

        $project = $this->projectFactory->createFromFile($projectConfigFile);
        $this->projectCompiler->compile($project);

    }
}