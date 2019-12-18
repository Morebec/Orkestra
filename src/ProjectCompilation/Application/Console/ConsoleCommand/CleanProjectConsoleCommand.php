<?php

namespace Morebec\Orkestra\ProjectCompilation\Application\Console\ConsoleCommand;

use Morebec\Orkestra\ProjectCompilation\Application\Console\Util\BytesFormatter;
use Morebec\Orkestra\ProjectCompilation\Application\Shared\Service\ApplicationService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CleanProjectConsoleCommand extends AbstractCommand
{
    protected static $defaultName = 'clean:project';
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    private $watching;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        ApplicationService $applicationService,
        LoggerInterface $logger
    ) {
        $this->watching = false;
        parent::__construct($applicationService, $logger);
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function configure()
    {
        $this
            ->setDescription('Cleans a Project ')
            ->setHelp('This command allows to clean the project from layer objects')

            ->addOption('config', null, InputOption::VALUE_OPTIONAL, 'Orkestra config file path')
        ;
    }

    public function exec(InputInterface $input, OutputInterface $output, SymfonyStyle $io): int
    {
        $io->section('Project cleaning');

        $t1 = time();
        $this->applicationService->cleanProject($this->project->getConfigurationFile());

        $time = time() - $t1;

        $output->writeln('');
        $output->writeln('<info>Project cleaned successfully</info>');
        $output->writeln('');

        $mem = BytesFormatter::formatFileSizeForHumans(memory_get_peak_usage(true));
        $io->writeln("Time: $time ms, Memory: $mem");

        return parent::STATUS_SUCCESS;
    }
}
