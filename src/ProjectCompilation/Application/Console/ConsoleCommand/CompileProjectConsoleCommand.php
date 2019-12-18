<?php

namespace Morebec\Orkestra\ProjectCompilation\Application\Console\ConsoleCommand;

use Exception;
use Morebec\Orkestra\ProjectCompilation\Application\Console\Service\FileWatcher\FileAddedEvent;
use Morebec\Orkestra\ProjectCompilation\Application\Console\Service\FileWatcher\FileDeletedEvent;
use Morebec\Orkestra\ProjectCompilation\Application\Console\Service\FileWatcher\FileModifiedEvent;
use Morebec\Orkestra\ProjectCompilation\Application\Console\Service\FileWatcher\FileWatcher;
use Morebec\Orkestra\ProjectCompilation\Application\Console\Util\BytesFormatter;
use Morebec\Orkestra\ProjectCompilation\Application\Shared\Service\ApplicationService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CompileProjectConsoleCommand extends AbstractCommand implements EventSubscriberInterface
{
    protected static $defaultName = 'compile:project';
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
            ->setDescription('Compiles a Project ')
            ->setHelp('This command allows to compile a Project')

            ->addOption('config', null, InputOption::VALUE_OPTIONAL, 'Orkestra config file path')
            ->addOption('watch', null, InputOption::VALUE_NONE, 'Add this option to watch directory for file changes')
        ;
    }

    public function exec(InputInterface $input, OutputInterface $output, SymfonyStyle $io): int
    {
        $io->section('Project compilation');

        $this->watching = $input->getOption('watch');

        $t1 = time();
        $this->compileProject();

        if ($this->watching) {
            $watcher = new FileWatcher($this->eventDispatcher);
            $watcher->addSubscriber($this);
            $watcher->watch($this->project->getModulesDirectory());
        }

        $time = time() - $t1;

        $output->writeln('');
        $output->writeln('<info>Project compiled successfully</info>');
        $output->writeln('');

        $mem = BytesFormatter::formatFileSizeForHumans(memory_get_peak_usage(true));
        $io->writeln("Time: $time ms, Memory: $mem");

        return parent::STATUS_SUCCESS;
    }

    /**
     * Compiles the project
     */
    private function compileProject(): void
    {
        $projectConfigFilePath = (string)$this->project->getConfigurationFile();

        // Clean first then compile
        $this->applicationService->cleanProject($projectConfigFilePath);
        $this->applicationService->compileProject($projectConfigFilePath);
        if ($this->watching) {
            $this->logger->info(PHP_EOL . 'Waiting for file changes ...' . PHP_EOL);
        }
    }

    /**
     * @param FileAddedEvent $e
     */
    public function onFileAdded(FileAddedEvent $e)
    {
        $this->logger->info($e->getFile() . ' Added');
        try {
            $this->compileProject();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @param FileModifiedEvent $e
     */
    public function onFileModified(FileModifiedEvent $e)
    {
        $this->logger->info($e->getFile() . ' Modified');
        try {
            $this->compileProject();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @param FileDeletedEvent $e
     */
    public function OnFileDeleted(FileDeletedEvent $e)
    {
        $this->logger->info($e->getFile() . ' Deleted');
        try {
            $this->compileProject();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            FileAddedEvent::class => 'onFileAdded',
            FileModifiedEvent::class => 'onFileModified',
            FileDeletedEvent::class => 'OnFileDeleted'
        ];
    }
}
