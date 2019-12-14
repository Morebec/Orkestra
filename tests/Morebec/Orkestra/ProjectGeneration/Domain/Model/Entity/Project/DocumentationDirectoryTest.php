<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project\DocumentationDirectory;
use Morebec\ValueObjects\File\Path;
use PHPUnit\Framework\TestCase;

class DocumentationDirectoryTest extends TestCase
{
    public function test__construct()
    {
        $d = new DocumentationDirectory(new Path('docs'));
        $this->assertNotNull($d);
    }

    public function test__constructBlankDirectoryPathThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $d = new DocumentationDirectory(new Path(''));
    }
}
