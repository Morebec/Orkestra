<?php

namespace Morebec\Orkestra\Workflow\Query;

class TermNode extends ExpressionNode
{
    /**
     * @var Term
     */
    private $term;

    /**
     * TermNode constructor.
     *
     * @param mixed $value
     */
    public function __construct(string $field, TermOperator $operator, $value)
    {
        $this->term = new Term($field, $operator, $value);
        parent::__construct();
    }

    public function __toString()
    {
        return (string) $this->term;
    }

    /**
     * Indicates if the term of this node matched a value.
     *
     * @param mixed $value
     */
    public function matches($value): bool
    {
        return $this->term->matches($value);
    }

    public function getField(): string
    {
        return $this->term->getField();
    }

    public function getTermOperator(): TermOperator
    {
        return $this->term->getOperator();
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->term->getValue();
    }
}
