<?php

namespace Tests\Morebec\Orkestra\Workflow\Query;

use InvalidArgumentException;
use Morebec\Orkestra\Workflow\Query\StringTerm;
use PHPUnit\Framework\TestCase;

class StringTermTest extends TestCase
{
    public function test__construct(): void
    {
        $term = new StringTerm('field === 5');
        $this->assertEquals('field === 5', (string) $term);

        $term = new StringTerm('field in ["a", "b", "c"]');
        $this->assertEquals('field in ["a","b","c"]', (string) $term);

        $this->expectException(InvalidArgumentException::class);
        $term = new StringTerm('');
    }
}
