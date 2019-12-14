<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Factory\ModuleFactory;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Factory\ProjectFactory;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Locator\ComposerConfigurationFileLocator;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Locator\ProjectConfigurationFileLocator;
use Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader\JsonComposerConfigurationLoader;
use Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader\YamlProjectConfigurationLoader;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Locator\ModuleConfigurationFilesLocator;

class ProjectTest extends \PHPUnit\Framework\TestCase
{
    private function createProject(): Project
    {
        $factory = new ProjectFactory(
            new JsonComposerConfigurationLoader(),
            new ComposerConfigurationFileLocator(),
            new YamlProjectConfigurationLoader(),
            $this->createMock(ModuleFactory::class),
            $this->createMock(ModuleConfigurationFilesLocator::class)
        );

        $projectConfigFile = (new ProjectConfigurationFileLocator())->locate(new Directory(new Path(getcwd())));
        $this->assertNotNull($projectConfigFile);
        return $factory->createFromFile($projectConfigFile);
    }

    public function test__construct()
    {
        $project = $this->createProject();
        $this->assertNotNull($project);
    }

    public function testGetTestsDirectory()
    {
        $project = $this->createProject();
        $this->assertNotNull($project->getTestsDirectory());
    }

    public function testGetModulesDirectory()
    {
        $project = $this->createProject();
        $this->assertNotNull($project->getModulesDirectory());
    }

    public function testGetSourceDirectory()
    {
        $project = $this->createProject();
        $this->assertNotNull($project->getSourceDirectory());
    }

    public function testGetNamespace()
    {
        $project = $this->createProject();
        $this->assertEquals("Morebec\Orkestra", (string)$project->getNamespace());
    }

    public function testGetModules()
    {
        $project = $this->createProject();
        $this->assertIsArray($project->getModules());
    }

    public function testGetDirectory()
    {
        $project = $this->createProject();
        $directory = $project->getDirectory();
        $this->assertNotNull($directory);
        $this->assertTrue($directory->exists());
    }

    public function testGetComposerConfiguration()
    {
        $project = $this->createProject();
        $cc = $project->getComposerConfiguration();
        $this->assertNotNull($cc);
    }
}
