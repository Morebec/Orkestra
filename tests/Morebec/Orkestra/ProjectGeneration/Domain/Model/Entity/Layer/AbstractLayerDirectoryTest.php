<?php

namespace ProjectGeneration\Domain\Model\Entity\Layer;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\AbstractLayerDirectory;
use Morebec\ValueObjects\File\Path;

class AbstractLayerDirectoryTest extends \PHPUnit\Framework\TestCase
{
    public function test__construct()
    {
        $mock = $this->getMockBuilder(AbstractLayerDirectory::class)->setConstructorArgs([new Path('a/path')]);
        $dir = $mock->getMock();
        $this->assertNotNull($dir);
    }

    public function test__constructWithBlankPathThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $mock = $this->getMockBuilder(AbstractLayerDirectory::class)->setConstructorArgs([new Path('')]);
        $dir = $mock->getMock();
    }
}
