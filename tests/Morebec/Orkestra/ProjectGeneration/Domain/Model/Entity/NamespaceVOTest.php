<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity;

use PHPUnit\Framework\TestCase;

/**
 * NamespaceVOTest
 */
class NamespaceVOTest extends TestCase
{
    public function test__construct()
    {
        $ns = new NamespaceVO('\MyNamespace');
        $this->assertNotNull($ns);
    }

    public function testBlankNamespaceThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $ns = new NamespaceVO('');
    }
    
    public function testAppendString()
    {
        $ns = new NamespaceVO('Orkestra\Namespace');
        
        $this->assertEquals('Orkestra\Namespace\Subspace', (string)$ns->appendString('Subspace'));
    }
}
