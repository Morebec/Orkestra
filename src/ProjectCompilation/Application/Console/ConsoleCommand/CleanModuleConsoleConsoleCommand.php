<?php

namespace Morebec\Orkestra\ProjectCompilation\Application\Console\ConsoleCommand;

use Morebec\Orkestra\Core\Application\Console\ConsoleCommand\AbstractConsoleCommand;
use Morebec\Orkestra\ProjectCompilation\Application\Console\Util\BytesFormatter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * CleanModuleConsoleCommand
 */
class CleanModuleConsoleConsoleCommand extends AbstractConsoleCommand
{
    protected static $defaultName = 'clean:module';

    protected function configure()
    {
        $this
                ->setDescription('Cleans a Module')
                ->setHelp('This command allows to clean a module from compiled layer objects')

                ->addArgument('name', InputArgument::REQUIRED, 'Module name')
        ;
    }
    
    public function exec(InputInterface $input, OutputInterface $output, SymfonyStyle $io): int
    {
        $moduleName = $input->getArgument('name');
        $projectConfigFilePath = $input->getOption('config');

        $io->section('Module cleaning');
        $io->listing(["Module name: <info>$moduleName</info>"]);


        $t1 = time();
        $this->applicationService->cleanModule($moduleName, $projectConfigFilePath);
        $time = time() - $t1;

        $io->writeln('');
        $io->writeln("<info>Module cleaned successfully</info>");
        $io->writeln('');

        $mem = BytesFormatter::formatFileSizeForHumans(memory_get_peak_usage(true));
        $io->writeln("Time: $time ms, Memory: $mem");
        return 0;
    }
}
