<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Factory;


use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfigurationFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Factory\ModuleFactory;
use Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader\YamlFileLoader;
use Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader\YamlModuleConfigurationLoader;
use Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader\YamlUseCaseConfigurationDataLoader;
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
        $useCaseLoader = new YamlUseCaseConfigurationDataLoader(new YamlFileLoader());
        $moduleFactory = new ModuleFactory($loader, $useCaseLoader);

        $module = $moduleFactory->createFromModuleConfigurationFile($moduleConfigurationFile);

        $this->assertNotNull($module);
    }
}
