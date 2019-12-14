<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project\SourceCodeDirectory;
use Morebec\ValueObjects\File\Path;
use PHPUnit\Framework\TestCase;

class SourceCodeDirectoryTest extends TestCase
{
    public function test__construct()
    {
        $d = new SourceCodeDirectory(new Path('src'));
        $this->assertNotNull($d);
    }

    public function test__constructBlankDirectoryPathThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $d = new SourceCodeDirectory(new Path(''));
    }
}
