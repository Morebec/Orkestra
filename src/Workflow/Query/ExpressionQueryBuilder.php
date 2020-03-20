<?php

namespace Morebec\Orkestra\Workflow\Query;

final class ExpressionQueryBuilder
{
    /** @var ExpressionNode */
    private $expression;

    /**
     * QueryBuilder constructor.
     *
     * @param mixed $value
     */
    private function __construct(string $field, TermOperator $operator, $value)
    {
        $this->expression = new ExpressionNode(new TermNode($field, $operator, $value));
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public static function where(string $field, TermOperator $operator, $value): self
    {
        return new static($field, $operator, $value);
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function andWhere(string $key, TermOperator $operator, $value): self
    {
        $where = new TermNode($key, $operator, $value);
        $this->insertNodeRight(ExpressionOperator::AND(), $where);

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function orWhere(string $key, TermOperator $operator, $value): self
    {
        $where = new TermNode($key, $operator, $value);
        $this->insertNodeRight(ExpressionOperator::OR(), $where);

        return $this;
    }

    public function build(): Query
    {
        return new Query($this->expression);
    }

    /**
     * Adds a note to the right of the root expresion.
     */
    private function insertNodeRight(ExpressionOperator $operator, TermNode $where): void
    {
        $this->expression = new ExpressionNode($this->expression, $operator, $where);
    }
}
