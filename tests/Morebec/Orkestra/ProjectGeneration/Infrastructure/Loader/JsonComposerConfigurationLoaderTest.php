<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service;

use Morebec\FileLocator\FileLocator;
use Morebec\FileLocator\FileLocatorStrategy;
use Morebec\Orkestra\ProjectGeneration\Infrastructure\Loader\JsonComposerConfigurationLoader;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;

/**
 * JsonComposerConfigLoaderTest
 */
class JsonComposerConfigurationLoaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     *
     */
    public function testLoadFile()
    {
        $locator = new FileLocator();        
        $composer = $locator->find(
                'composer.json', 
                new Directory(new Path(getcwd())),
                FileLocatorStrategy::RECURSIVE_UP()
        );
        
        $loader = new JsonComposerConfigurationLoader();
        $composerConf = $loader->load($composer);
        
        $this->assertNotNull($composerConf);
    }
}
