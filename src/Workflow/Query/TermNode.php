<?php

namespace Morebec\Orkestra\Workflow\Query;

use LogicException;

class TermNode extends ExpressionNode
{
    /**
     * @var Term
     */
    private $term;

    /**
     * TermNode constructor.
     * @param string $field
     * @param TermOperator $operator
     * @param mixed $value
     */
    public function __construct(string $field, TermOperator $operator, $value)
    {
        $this->term = new Term($field, $operator, $value);
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->term->getField();
    }

    /**
     * @return TermOperator
     */
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

    public function __toString()
    {
        return (string)$this->term;
    }
}
