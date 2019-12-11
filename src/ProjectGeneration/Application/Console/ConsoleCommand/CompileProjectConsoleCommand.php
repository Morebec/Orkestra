<?php

namespace Morebec\Orkestra\ProjectGeneration\Application\Console\ConsoleCommand;

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

        $infoSection = $output->section();
        $infoSection->writeln([
            'Project compilation',
            '==================',
            "Project Configuration file: <info>$projectConfigFilePath</info>"
        ]);

        $output->writeln('Compiling module ...');
        $this->applicationService->compileProject($projectConfigFilePath);
        $output->writeln('<info>Project compiled successfully</info>');

        return parent::STATUS_SUCCESS;
    }
}