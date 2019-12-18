<?php

namespace Morebec\Orkestra\ProjectCompilation\Application\Console\ConsoleCommand;

use Exception;
use Morebec\Orkestra\ProjectCompilation\Application\Shared\Orkestra;
use Morebec\Orkestra\ProjectCompilation\Application\Shared\Service\ApplicationService;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project\Project;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(ApplicationService $applicationService, LoggerInterface $logger)
    {
        $this->applicationService = $applicationService;
        $this->logger = $logger;
        parent::__construct(null);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $projectConfigFilePath = $input->getOption('config');
        $io = new SymfonyStyle($input, $output);

        try {
            $this->project = $this->applicationService->getProject($projectConfigFilePath);
            $projectConfigFilePath = $this->project->getConfigurationFile();

            $io->title(sprintf("<info>Orkestra v%s</info>", Orkestra::VERSION));

            $io->listing(["Project configuration file: <info>$projectConfigFilePath</info>"]);

            $statusCode = $this->exec($input, $output, $io);
            $output->writeln('');
        } catch (Exception $e) {
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
    abstract public function exec(InputInterface $input, OutputInterface $output, SymfonyStyle $io): int;
}
