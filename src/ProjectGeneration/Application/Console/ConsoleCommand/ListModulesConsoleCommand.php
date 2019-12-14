<?php

namespace Morebec\Orkestra\ProjectGeneration\Application\Console\ConsoleCommand;

use Stringy\Stringy as Str;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListModulesConsoleCommand extends AbstractCommand
{
    protected static $defaultName = 'project:modules';

    protected function configure()
    {
        $this
            ->setDescription('Compile a Module')
            ->setHelp('This command allows to compile a module')
            ->addOption('config', null, InputOption::VALUE_OPTIONAL, 'Orkestra config file path')
        ;
    }

    public function exec(InputInterface $input, OutputInterface $output, SymfonyStyle $io): int
    {
        $output->writeln([
            'List Modules',
            '==================',
        ]);

        $modules = $this->project->getModules();

        if(!count($modules)) {
            $io->warning('There are no modules in this project');
            return parent::STATUS_SUCCESS;
        }

        $table = new Table($output);
        $table->setHeaders(['Name', 'Namespace', 'Directory', 'Configuration File Path']);

        $cwd = getcwd();

        foreach ($modules as $module) {

            $dir = Str::create((string)$module->getDirectory())->replace($cwd, '.');
            $configFile = Str::create((string)$module->getConfigurationFile())->replace($cwd, '.');
            $table->addRow([$module->getName(), $module->getNamespace(), $dir, $configFile]);
        }


        $table->render();


        return parent::STATUS_SUCCESS;
    }
}