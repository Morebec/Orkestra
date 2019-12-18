<?php


namespace Morebec\Orkestra\ProjectGeneration\Application\Console\ConsoleCommand;


use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\OCFile;
use Morebec\ValueObjects\File\File;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Yaml\Yaml;

class GenerateCustomStubConsoleConsoleCommand extends AbstractGenerateSchemaConsoleConsoleCommand
{
    protected static $defaultName = 'gen:stub';

    protected $objectTypeName = 'Stub';

    /**
     * @inheritDoc
     */
    protected function getStub($objectName): array
    {
        $config = $this->project->getConfiguration();

        $stubsDirectory = $config->getStubsDirectory();

        $stubFile = File::fromStringPath("{$stubsDirectory}/{$this->objectTypeName}." . OCFile::EXTENSION);

        if(!$stubFile->exists()) {
            throw new \InvalidArgumentException("Stub {$stubFile} not found");
        }

        $content = $stubFile->getContent();

        $content = str_replace('%name%', $objectName, $content);

        return Yaml::parse($content);
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate Entity')
            ->setHelp('This command allows to create an entity schema file')

            ->addArgument('stub', InputArgument::REQUIRED, 'Name of the stub to use')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the object to generate')
        ;

        $this->addOption('config', null, InputOption::VALUE_OPTIONAL, 'Orkestra config file path');
    }

    public function exec(InputInterface $input, OutputInterface $output, SymfonyStyle $io): int
    {
        $this->objectTypeName = $input->getArgument('stub');

        return parent::exec($input, $output, $io);
    }
}