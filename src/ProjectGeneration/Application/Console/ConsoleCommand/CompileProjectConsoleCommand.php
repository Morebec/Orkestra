<?php

namespace Morebec\Orkestra\ProjectGeneration\Application\Console\ConsoleCommand;

use Morebec\Orkestra\ProjectGeneration\Application\Console\Util\BytesFormatter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CompileProjectConsoleCommand extends AbstractCommand
{
    protected static $defaultName = 'compile:project';

    protected function configure()
    {
        $this
            ->setDescription('Compiles a Project ')
            ->setHelp('This command allows to compile a Project')

            ->addOption('config', null, InputOption::VALUE_OPTIONAL, 'Orkestra config file path')
        ;
    }

    public function exec(InputInterface $input, OutputInterface $output, SymfonyStyle $io): int
    {
        $projectConfigFilePath = $input->getOption('config');

        $io->section('Project compilation');

        $t1 = time();
        $this->applicationService->compileProject($projectConfigFilePath);
        $time = time() - $t1;

        $output->writeln('');
        $output->writeln('<info>Project compiled successfully</info>');
        $output->writeln('');

        $mem = BytesFormatter::formatFileSizeForHumans(memory_get_peak_usage(true));
        $io->writeln("Time: $time ms, Memory: $mem");

        return parent::STATUS_SUCCESS;
    }
}