<?php

namespace Tests\Morebec\Orkestra\Workflow\Query;

use Morebec\Orkestra\Workflow\Query\ExpressionQueryBuilder;
use Morebec\Orkestra\Workflow\Query\TermOperator;
use PHPUnit\Framework\TestCase;

class ExpressionQueryBuilderTest extends TestCase
{
    public function testWhere()
    {
        $expr = ExpressionQueryBuilder::where('field', TermOperator::EQUAL(), 55)->build();
        $this->assertEquals('field === 55', (string) $expr);

        $expr = ExpressionQueryBuilder::where('price', TermOperator::GREATER_OR_EQUAL(), 45)
                             ->andWhere('genre', TermOperator::IN(), ['sci-fi', 'poetry'])->build();
        $this->assertEquals('(price >= 45) AND (genre in ["sci-fi","poetry"])', (string) $expr);
    }
}
