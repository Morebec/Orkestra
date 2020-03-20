<?php

namespace Morebec\Orkestra\Workflow\Query;

final class QueryBuilder
{
    /** @var ExpressionQueryBuilder */
    private $expressionBuilder;

    /**
     * QueryBuilder constructor.
     */
    public function __construct(StringTerm $term)
    {
        $this->expressionBuilder = ExpressionQueryBuilder::where(
            $term->getField(),
            $term->getTermOperator(),
            $term->getValue()
        );
    }

    /**
     * @return static
     */
    public static function where(string $term): self
    {
        return new static(new StringTerm($term));
    }

    /**
     * @return $this
     */
    public function andWhere(string $term): self
    {
        $t = new StringTerm($term);
        $this->expressionBuilder->andWhere($t->getField(), $t->getTermOperator(), $t->getValue());

        return $this;
    }

    /**
     * @return $this
     */
    public function orWhere(string $term): self
    {
        $t = new StringTerm($term);
        $this->expressionBuilder->andWhere($t->getField(), $t->getTermOperator(), $t->getValue());

        return $this;
    }

    public function build(): Query
    {
        return $this->expressionBuilder->build();
    }
}
