<?php

namespace Morebec\Orkestra\ProjectCompilation\Application\Console\ConsoleCommand;

use Morebec\Orkestra\Core\Application\Console\ConsoleCommand\AbstractConsoleCommand;
use Stringy\Stringy as Str;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListModulesConsoleConsoleCommand extends AbstractConsoleCommand
{
    protected static $defaultName = 'project:modules';

    protected function configure()
    {
        $this
            ->setDescription('List Project Modules')
            ->setHelp('This command allows to list the modules of a project')
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

        if (!count($modules)) {
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