<?php

namespace ProjectGeneration\Infrastructure\ConfigurationLoader;

use Morebec\Orkestra\ProjectGeneration\Domain\Service\Locator\ProjectConfigurationFileLocator;
use Morebec\Orkestra\ProjectGeneration\Infrastructure\Loader\YamlProjectConfigurationLoader;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;
use PHPUnit\Framework\TestCase;

class YamlProjectConfigurationLoaderTest extends TestCase
{

    public function testLoad()
    {
        $locator = new ProjectConfigurationFileLocator();
        $pcf = $locator->locate(new Directory(new Path(getcwd())));

        $this->assertNotNull($pcf);

        $loader = new YamlProjectConfigurationLoader();
        $conf = $loader->load($pcf);

        $this->assertNotNull($conf);
    }
}
