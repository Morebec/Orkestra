<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Entity;


use Morebec\ValueObjects\File\Path;

class EntitySchemaFileTest extends \PHPUnit\Framework\TestCase
{

    public function test__construct()
    {
        $es = new EntitySchemaFile(new Path('entity.oc'));
        $this->assertNotNull($es);
    }

    public function test__constructWithInvalidExtensionThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $es = new EntitySchemaFile(new Path('entity.wrong_ext'));
    }
}
