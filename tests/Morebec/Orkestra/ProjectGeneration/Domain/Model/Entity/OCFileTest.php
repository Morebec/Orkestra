<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\OCFile;
use Morebec\ValueObjects\File\Path;

class OCFileTest extends \PHPUnit\Framework\TestCase
{
    public function test__construct(): void
    {
        $ocf = new OCFile(new Path('myfile.oc'));
        $this->assertNotNull($ocf);
    }

    public function test__constructWithWrongExtensionThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new OCFile(new Path(__FILE__)); // The file does not have an .oc extension, it should throw an exception
    }
}
