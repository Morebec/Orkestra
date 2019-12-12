<?php

namespace ProjectGeneration\Domain\Model\Factory;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Factory\ModuleFactory;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Factory\ProjectFactory;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Locator\ComposerConfigurationFileLocator;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Locator\ModuleConfigurationFilesLocator;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Locator\ProjectConfigurationFileLocator;
use Morebec\Orkestra\ProjectGeneration\Infrastructure\Loader\JsonComposerConfigurationLoader;
use Morebec\Orkestra\ProjectGeneration\Infrastructure\Loader\YamlProjectConfigurationLoader;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;

class ProjectFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function test__construct()
    {
        $factory = new ProjectFactory(
            new JsonComposerConfigurationLoader(),
            new ComposerConfigurationFileLocator(),
            new YamlProjectConfigurationLoader(),
            $this->createMock(ModuleFactory::class),
            $this->createMock(ModuleConfigurationFilesLocator::class)
        );
        $this->assertNotNull($factory);
    }

    public function testCreateFromFile()
    {
        $factory = new ProjectFactory(
            new JsonComposerConfigurationLoader(),
            new ComposerConfigurationFileLocator(),
            new YamlProjectConfigurationLoader(),
            $this->createMock(ModuleFactory::class),
            $this->createMock(ModuleConfigurationFilesLocator::class)
        );

        $projectConfigFile = (new ProjectConfigurationFileLocator())->locate(new Directory(new Path(getcwd())));
        $project = $factory->createFromFile($projectConfigFile);
        $this->assertNotNull($project);
    }
}
