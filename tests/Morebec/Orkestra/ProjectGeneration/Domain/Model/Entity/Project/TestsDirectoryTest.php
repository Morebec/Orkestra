<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project\DocumentationDirectory;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project\TestsDirectory;
use Morebec\ValueObjects\File\Path;
use PHPUnit\Framework\TestCase;

class TestsDirectoryTest extends TestCase
{
    public function test__construct()
    {
        $d = new TestsDirectory(new Path('tests'));
        $this->assertNotNull($d);
    }

    public function test__constructBlankDirectoryPathThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $d = new TestsDirectory(new Path(''));
    }
}