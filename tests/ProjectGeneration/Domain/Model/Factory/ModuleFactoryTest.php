<?php

namespace ProjectGeneration\Domain\Model\Factory;


use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module\ModuleConfigurationFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Factory\ModuleFactory;
use Morebec\Orkestra\ProjectGeneration\Infrastructure\ConfigurationLoader\YamlModuleConfigurationLoader;
use Morebec\ValueObjects\File\FileContent;
use Symfony\Component\Yaml\Yaml;

class ModuleFactoryTest extends \PHPUnit\Framework\TestCase
{

    public function testCreateFromModuleConfigurationFile()
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
        $moduleFactory = new ModuleFactory($loader);

        $module = $moduleFactory->createFromModuleConfigurationFile($moduleConfigurationFile);

        $this->assertNotNull($module);
    }
}
