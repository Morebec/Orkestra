<?php

namespace Tests\Morebec\Orkestra\Workflow\Query;

use Morebec\Orkestra\Workflow\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    public function testWhere(): void
    {
        $expr = QueryBuilder::where('field === 55')->build();
        $this->assertEquals('field === 55', (string)$expr);

        $expr = QueryBuilder::where('price >= 45')
            ->andWhere('genre in ["sci-fi", "poetry"]')->build();
        $this->assertEquals('(price >= 45) AND (genre in ["sci-fi","poetry"])', (string)$expr);
    }
}
