<?php

use Morebec\Orkestra\ProjectGeneration\Domain\Service\Locator\ProjectConfigurationFileLocator;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;
use PHPUnit\Framework\TestCase;

/**
 * Project Configuration File Locator
 */
class ProjectConfigurationFileLocatorTest extends TestCase
{
    /**
     *
     */
    public function testLocate()
    {
        $locator = new ProjectConfigurationFileLocator();
        $file = $locator->locate(new Directory(new Path(getcwd())));

        $this->assertNotNull($file);
    }
}
