<?php

namespace Morebec\Orkestra\ProjectGeneration\Application\Console\ConsoleCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * CompileModuleConsoleCommand
 */
class CompileModuleConsoleCommand extends AbstractCommand
{
    protected static $defaultName = 'compile:module';

    protected function configure()
    {
        $this
                ->setDescription('Compile a Module')
                ->setHelp('This command allows to compile a module')
                
                ->addArgument('name', InputArgument::REQUIRED, 'Module name')
                ->addOption('config', null, InputOption::VALUE_OPTIONAL, 'Orkestra config file path')
        ;
    }
    
    public function exec(InputInterface $input, OutputInterface $output, SymfonyStyle $io): int
    {
        $moduleName = $input->getArgument('name');
        $projectConfigFilePath = $input->getOption('config');

        $io->section('Module compilation');
        $io->listing(["Module name: <info>$moduleName</info>"]);


        $output->writeln('Compiling module ...');

        $t1 = time();
        $this->applicationService->compileModule($moduleName, $projectConfigFilePath);
        $time = time() - $t1;

        $io->writeln('');
        $io->writeln("<info>Module compiled successfully</info>");
        $io->writeln('');

        $mem = $this->getNiceFileSize(memory_get_peak_usage(true));
        $io->writeln("Time: $time ms, Memory: $mem");
        return 0;
    }

    /**
     * Nicely formats memory usage
     * @param $bytes
     * @param bool $binaryPrefix
     * @return string
     */
    private function getNiceFileSize($bytes, $binaryPrefix=true): string {
        if ($binaryPrefix) {
            $unit=array('B','KiB','MiB','GiB','TiB','PiB');
            if ($bytes==0) return '0 ' . $unit[0];
            return @round($bytes/pow(1024,($i=floor(log($bytes,1024)))),2) .' '. (isset($unit[$i]) ? $unit[$i] : 'B');
        } else {
            $unit=array('B','KB','MB','GB','TB','PB');
            if ($bytes==0) return '0 ' . $unit[0];
            return @round($bytes/pow(1000,($i=floor(log($bytes,1000)))),2) .' '. (isset($unit[$i]) ? $unit[$i] : 'B');
        }
    }
}
