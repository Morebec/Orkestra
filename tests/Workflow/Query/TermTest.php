<?php

namespace Tests\Morebec\Orkestra\Workflow\Query;

use InvalidArgumentException;
use Morebec\Orkestra\Workflow\Query\Term;
use Morebec\Orkestra\Workflow\Query\TermOperator;
use PHPUnit\Framework\TestCase;

class TermTest extends TestCase
{
    public function test__construct(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $term = new Term('field', TermOperator::IN(), 'a');
    }

    public function testMatches(): void
    {
        $term = new Term('field', TermOperator::EQUAL(), 50);
        $this->assertTrue($term->matches(50));
        $this->assertFalse($term->matches(89));

        $term = new Term('field', TermOperator::NOT_EQUAL(), 50);
        $this->assertFalse($term->matches(50));
        $this->assertTrue($term->matches(55));

        $term = new Term('field', TermOperator::LESS_THAN(), 50);
        $this->assertFalse($term->matches(50));
        $this->assertTrue($term->matches(25));

        $term = new Term('field', TermOperator::GREATER_THAN(), 50);
        $this->assertFalse($term->matches(50));
        $this->assertTrue($term->matches(55));

        $term = new Term('field', TermOperator::LESS_OR_EQUAL(), 50);
        $this->assertFalse($term->matches(55));
        $this->assertTrue($term->matches(50));
        $this->assertTrue($term->matches(22));

        $term = new Term('field', TermOperator::GREATER_OR_EQUAL(), 50);
        $this->assertFalse($term->matches(25));
        $this->assertTrue($term->matches(50));
        $this->assertTrue($term->matches(50));

        $term = new Term('field', TermOperator::IN(), ['a', 'b', 'c']);
        $this->assertFalse($term->matches('d'));
        $this->assertTrue($term->matches('a'));

        $term = new Term('field', TermOperator::NOT_IN(), ['a', 'b', 'c']);
        $this->assertFalse($term->matches('a'));
        $this->assertTrue($term->matches('d'));

        $term = new Term('field', TermOperator::CONTAINS(), 'a');
        $this->assertFalse($term->matches(['b', 'c', 'd']));
        $this->assertTrue($term->matches(['a', 'b', 'c']));

        $term = new Term('field', TermOperator::NOT_CONTAINS(), 'a');
        $this->assertTrue($term->matches(['b', 'c', 'd']));
        $this->assertFalse($term->matches(['a', 'b', 'c']));
    }
}
