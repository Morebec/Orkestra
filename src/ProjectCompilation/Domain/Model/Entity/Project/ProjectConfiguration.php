<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project;

use Assert\Assertion;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModulesConfigurationDirectory;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\File;
use Morebec\ValueObjects\File\Path;

/**
 * Corresponds to configuration of a Project and all its configuration values.

 */
class ProjectConfiguration 
{
    const MODULES_DIRECTORY_KEY = 'modules_directory';

    const TESTS_DIRECTORY_KEY = 'tests_directory';

    const SOURCE_DIRECTORY_KEY = 'source_directory';

    const DOCUMENTATION_DIRECTORY_KEY = 'documentation_directory';

    /**
     * File pointing to the configuration of the Project
     * @var ProjectConfigurationFile
     */
    private $configFile;
    
    /**
     * Directory where PHP source files are located
     * @var SourceCodeDirectory
     */
    private $sourceDirectory;
    
    /**
     * Directory where PHP tests files are located
     * @var TestsDirectory
     */
    private $testsDirectory;
    
    /**
     * Directory where Module configuration files are located
     * @var ModulesConfigurationDirectory
     */
    private $modulesDirectory;

    /**
     * Directory where the documentation is located
     * @var DocumentationDirectory
     */
    private $documentationDirectory;

    private function __construct(ProjectConfigurationFile $configFile)
    {
        $this->configFile = $configFile;
    }

    /**
     * Returns the directory containing the project
     * @return Directory|null
     * @throws \Exception
     */
    public function getProjectDirectory(): ?Directory
    {
        return $this->configFile->getDirectory();
    }

    public function getConfigurationFile(): ProjectConfigurationFile
    {
        return $this->configFile;
    }

    public function getSourceDirectory(): SourceCodeDirectory
    {
        return $this->sourceDirectory;
    }

    public function getTestsDirectory(): TestsDirectory
    {
        return $this->testsDirectory;
    }

    public function getModulesDirectory(): ModulesConfigurationDirectory
    {
        return $this->modulesDirectory;
    }

    public function getDocumentationDirectory(): DocumentationDirectory
    {
        return $this->documentationDirectory;
    }

    private function setSourceDirectory(SourceCodeDirectory $sourceDirectory): void
    {
        Assertion::notBlank($sourceDirectory);
        $this->sourceDirectory = $sourceDirectory;
    }

    private function setTestsDirectory(TestsDirectory $testsDirectory): void
    {
        Assertion::notBlank($testsDirectory);
        $this->testsDirectory = $testsDirectory;
    }

    private function setModulesDirectory(ModulesConfigurationDirectory $modulesDirectory): void
    {
        Assertion::notBlank($modulesDirectory);
        $this->modulesDirectory = $modulesDirectory;
    }

    private function setDocumentationDirectory(DocumentationDirectory $documentationDirectory)
    {
        Assertion::notBlank($documentationDirectory);
        $this->documentationDirectory = $documentationDirectory;
    }

    /**
     * Builds an instance of project configuration object from an array
     * @param ProjectConfigurationFile $configurationFile
     * @param array $data
     * @return ProjectConfiguration
     */
    public static function fromArray(ProjectConfigurationFile $configurationFile, array $data): self
    {
        $conf = new static($configurationFile);

        $projectDirectory = $configurationFile->getDirectory();

        $conf->setSourceDirectory(new SourceCodeDirectory(
                new Path($projectDirectory . '/' . $data[self::SOURCE_DIRECTORY_KEY]))
        );
        
        $conf->setTestsDirectory(new TestsDirectory(
                new Path($projectDirectory . '/' . $data[self::TESTS_DIRECTORY_KEY]))
        );

        $conf->setDocumentationDirectory(new DocumentationDirectory(
                new Path($projectDirectory . '/' . $data[self::DOCUMENTATION_DIRECTORY_KEY]))
        );
        
        $conf->setModulesDirectory(new ModulesConfigurationDirectory(
                new Path($projectDirectory . '/' . $data[self::MODULES_DIRECTORY_KEY]))
        );
        
        return $conf;
    }
}