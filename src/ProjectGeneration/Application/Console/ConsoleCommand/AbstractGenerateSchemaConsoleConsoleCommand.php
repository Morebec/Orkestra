<?php


namespace Morebec\Orkestra\ProjectGeneration\Application\Console\ConsoleCommand;


use Morebec\Orkestra\Core\Application\Console\ConsoleCommand\AbstractConsoleCommand;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\OCFile;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

abstract class AbstractGenerateSchemaConsoleConsoleCommand extends AbstractConsoleCommand
{
    protected static $defaultName = 'gen:entity';

    protected $objectTypeName = 'Object';

    /**
     * @param $objectName
     * @return array
     */
    protected abstract function getStub($objectName): array;

    protected function configure()
    {
        parent::configure();
        $this
            ->setDescription("Generate {$this->objectTypeName}")
            ->setHelp("This command allows to create an {$this->objectTypeName} schema file")

            ->addArgument('name', InputArgument::REQUIRED, "Name of the {$this->objectTypeName}")
        ;
    }

    /**
     * @inheritDoc
     */
    public function exec(InputInterface $input, OutputInterface $output, SymfonyStyle $io): int
    {
        $objectName = $input->getArgument('name');

        $objectSchemaFilename = sprintf("%s.%s", $objectName, OCFile::EXTENSION);

        $io->section("{$this->objectTypeName} Schema Generation");
        $io->listing(["Entity name: <info>$objectName</info>"]);
        $io->listing(["Schema filename: <info>{$objectSchemaFilename}</info>"]);


        $stub = Yaml::dump(
            $this->getStub($objectName),
            10,
            2,
            Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK | Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK
        );

        file_put_contents($objectSchemaFilename, $stub);

        $io->writeln(PHP_EOL . "<info>{$this->objectTypeName} generated successfully</info>" . PHP_EOL);

        return self::STATUS_SUCCESS;
    }
}