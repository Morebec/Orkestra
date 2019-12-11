<?php

namespace Morebec\Orkestra\ProjectGeneration\Application\Console\ConsoleCommand;

use Exception;
use Morebec\Orkestra\ProjectGeneration\Application\Shared\Orkestra;
use Morebec\Orkestra\ProjectGeneration\Application\Shared\Service\ApplicationService;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\Project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * AbstractCommand giving acess to the application service
 */
abstract class AbstractCommand extends Command
{
    public const STATUS_SUCCESS = 0;
    public const STATUS_ERROR = 1;

    /**
     * @var ApplicationService
     */
    protected $applicationService;
    /**
     * @var Project
     */
    protected $project;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
        parent::__construct(null);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $projectConfigFilePath = $input->getOption('config');
        $io = new SymfonyStyle($input, $output);

        try {
            $this->project = $this->applicationService->getProject($projectConfigFilePath);
            $projectConfigFilePath = $this->project->getConfigurationFile();

            /** @var ConsoleSectionOutput $headerSection */


            $io->title(sprintf("<info>Orkestra v%s</info>", Orkestra::VERSION));

            $io->listing(["Project configuration file: <info>$projectConfigFilePath</info>"]);

            $statusCode = $this->exec($input, $output, $io);
            $output->writeln('');
        } catch(Exception $e) {
            $statusCode = self::STATUS_ERROR;
            $message = $e->getMessage();
            $io->getErrorStyle()->error($e->getMessage());
        }

        return $statusCode;
    }

    /**
     * Executes the command and returns it status code
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param SymfonyStyle $io
     * @return int
     */
    public abstract function exec(InputInterface $input, OutputInterface $output, SymfonyStyle $io): int;
}