<?php

namespace Morebec\Orkestra\ProjectGeneration\Infrastructure\ConfigurationLoader;


use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module\ModuleConfigurationFile;
use Morebec\ValueObjects\File\FileContent;
use Morebec\ValueObjects\File\Path;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class YamlModuleConfigurationLoaderTest extends TestCase
{
    public function testLoadWithFileThatDoesNotExistThrowsException(): void
    {
        $loader = new YamlModuleConfigurationLoader();
        $this->expectException(\InvalidArgumentException::class);
        $loader->load(new ModuleConfigurationFile(new Path('does-not-exist.oc')));
    }

    public function testLoad(): void
    {
        $moduleData = [
            'TestModule' => [
                'Application' => [],
                'Domain' => [],
                'Infrastructure' => []
            ]
        ];

        $moduleConfigurationFile = $this->createMock(ModuleConfigurationFile::class);
        $moduleConfigurationFile->method('getContent')->willReturn(new FileContent(Yaml::dump($moduleData)));
        $moduleConfigurationFile->method('exists')->willReturn(true);
        $moduleConfigurationFile->method('__toString')->willReturn('module.oc');

        $loader = new YamlModuleConfigurationLoader();
        $moduleConfiguration = $loader->load($moduleConfigurationFile);

        $this->assertNotNull($moduleConfiguration);
    }
}
