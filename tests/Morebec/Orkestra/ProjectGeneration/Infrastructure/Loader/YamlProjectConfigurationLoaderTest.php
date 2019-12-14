<?php

namespace Morebec\Orkestra\ProjectCompilation\Infrastructure\ConfigurationLoader;

use Morebec\Orkestra\ProjectCompilation\Domain\Service\Locator\ProjectConfigurationFileLocator;
use Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader\YamlProjectConfigurationLoader;
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
