<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project;

use Assert\Assertion;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModulesConfigurationDirectory;
use Morebec\ValueObjects\File\Directory;
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

    const MODULE_OBJECT_TEMPLATES_DIRECTORY = 'layer_object_templates_directory';

    const STUBS_DIRECTORY_KEY = 'stubs_directory';

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

    /**
     * Directory where layer object templates are located
     * @var Directory
     */
    private $moduleObjectTemplatesDirectory;

    /**
     * @var Directory
     */
    private $stubsDirectory;


    private function __construct(ProjectConfigurationFile $configFile)
    {
        $this->configFile = $configFile;
    }

    /**
     * Returns the directory containing the project
     * @return Directory|null
     */
    public function getProjectDirectory(): ?Directory
    {
        return $this->configFile->getDirectory();
    }

    /**
     * @return ProjectConfigurationFile
     */
    public function getConfigurationFile(): ProjectConfigurationFile
    {
        return $this->configFile;
    }

    /**
     * @return SourceCodeDirectory
     */
    public function getSourceDirectory(): SourceCodeDirectory
    {
        return $this->sourceDirectory;
    }

    /**
     * @return TestsDirectory
     */
    public function getTestsDirectory(): TestsDirectory
    {
        return $this->testsDirectory;
    }

    /**
     * @return ModulesConfigurationDirectory
     */
    public function getModulesDirectory(): ModulesConfigurationDirectory
    {
        return $this->modulesDirectory;
    }

    /**
     * @return DocumentationDirectory
     */
    public function getDocumentationDirectory(): DocumentationDirectory
    {
        return $this->documentationDirectory;
    }

    /**
     * @return LayerObjectTemplateDirectory
     */
    public function getModuleObjectTemplatesDirectory(): Directory
    {
        return $this->moduleObjectTemplatesDirectory;
    }

    /**
     * @return Directory
     */
    public function getStubsDirectory(): Directory
    {
        return $this->stubsDirectory;
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

    private function setDocumentationDirectory(DocumentationDirectory $documentationDirectory): void
    {
        Assertion::notBlank($documentationDirectory);
        $this->documentationDirectory = $documentationDirectory;
    }

    private function setModuleObjectTemplatesDirectory(Directory $templateDirectory): void
    {
        Assertion::notBlank($templateDirectory);
        $this->moduleObjectTemplatesDirectory = $templateDirectory;
    }

    /**
     * @param Directory $stubsDirectory
     */
    public function setStubsDirectory(Directory $stubsDirectory): void
    {
        $this->stubsDirectory = $stubsDirectory;
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

        $conf->setSourceDirectory(
            new SourceCodeDirectory(
            new Path("{$projectDirectory}/{$data[self::SOURCE_DIRECTORY_KEY]}")
        )
        );
        
        $conf->setTestsDirectory(
            new TestsDirectory(
            new Path("{$projectDirectory}/{$data[self::TESTS_DIRECTORY_KEY]}")
        )
        );

        $conf->setDocumentationDirectory(
            new DocumentationDirectory(
            new Path("{$projectDirectory}/{$data[self::DOCUMENTATION_DIRECTORY_KEY]}")
        )
        );
        
        $conf->setModulesDirectory(
            new ModulesConfigurationDirectory(
            new Path("{$projectDirectory}/{$data[self::MODULES_DIRECTORY_KEY]}")
        )
        );

        $templatesDirName = 'orkestra/templates';

        if(array_key_exists(self::MODULE_OBJECT_TEMPLATES_DIRECTORY, $data)) {
            $templatesDirName = $data[self::MODULE_OBJECT_TEMPLATES_DIRECTORY];
        }
        $conf->setModuleObjectTemplatesDirectory(new LayerObjectTemplateDirectory(
            new Path("{$projectDirectory}/{$templatesDirName}")
        ));

        $stubsDirName = 'orkestra/stubs';
        if(array_key_exists(self::STUBS_DIRECTORY_KEY, $data)) {
            $stubsDirName = $data[self::STUBS_DIRECTORY_KEY];
        }

        $conf->setStubsDirectory(new Directory(new Path("{$projectDirectory}/{$stubsDirName}")));
        
        return $conf;
    }
}
