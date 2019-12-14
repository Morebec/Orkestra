<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project\ProjectConfigurationFile;
use Morebec\ValueObjects\File\Path;

class ProjectConfigurationFileTest extends \PHPUnit\Framework\TestCase
{
    public function test__cosntruct()
    {
        $pcf = new ProjectConfigurationFile(new Path('orkestra.oc'));
        $this->assertNotNull($pcf);
    }

    public function test__constructWithNonValidNameThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $pcf = new ProjectConfigurationFile(new Path('this-should-be-orkestra.oc'));
    }
}
