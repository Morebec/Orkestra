<?php

namespace Tests\Morebec\Orkestra\Normalization\Denormalizer\ObjectDenormalizer;

use Morebec\Orkestra\Normalization\Denormalizer\ObjectDenormalizer\DoctrineClassInstantiator;
use PHPUnit\Framework\TestCase;

class DoctrineClassInstantiatorTest extends TestCase
{
    public function testInstantiate(): void
    {
        $instantiator = new DoctrineClassInstantiator();
        $test = $instantiator->instantiate(self::class);

        $this->assertInstanceOf(self::class, $test);
    }
}
