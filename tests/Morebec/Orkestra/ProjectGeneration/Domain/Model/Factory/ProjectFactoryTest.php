<?php

namespace  Morebec\Orkestra\ProjectCompilation\Domain\Model\Factory;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Factory\ModuleFactory;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Factory\ProjectFactory;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Locator\ComposerConfigurationFileLocator;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Locator\ModuleConfigurationFilesLocator;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Locator\ProjectConfigurationFileLocator;
use Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader\JsonComposerConfigurationLoader;
use Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader\YamlProjectConfigurationLoader;
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
