<?php

namespace Tests\Morebec\Orkestra\Workflow\Query;

use Morebec\Orkestra\Workflow\Query\QueryBuilder;
use Morebec\Orkestra\Workflow\Query\TermOperator;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    public function testWhere()
    {
        $expr = QueryBuilder::where('field', TermOperator::EQUAL(), 55)->build();
        $this->assertEquals('field === 55', (string)$expr);

        $expr = QueryBuilder::where('price', TermOperator::GREATER_OR_EQUAL(), 45)
                             ->andWhere('genre', TermOperator::IN(), ['sci-fi', 'poetry'])->build();
        $this->assertEquals("(price >= 45) OR (genre in ['sci-fi', 'poetry'])", (string)$expr);
    }
}
