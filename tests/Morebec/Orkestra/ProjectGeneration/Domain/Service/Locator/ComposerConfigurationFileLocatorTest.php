<?php

namespace ProjectGeneration\Domain\Service\Locator;

use Morebec\Orkestra\ProjectGeneration\Domain\Service\Locator\ComposerConfigurationFileLocator;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;

class ComposerConfigurationFileLocatorTest extends \PHPUnit\Framework\TestCase
{

    public function testLocate()
    {
        $locator = new ComposerConfigurationFileLocator();
        $file = $locator->locate(new Directory(new Path(getcwd())));

        $this->assertNotNull($file);
    }
}
