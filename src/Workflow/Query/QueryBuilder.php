<?php


namespace Morebec\Orkestra\Workflow\Query;

use Doctrine\Common\Collections\ExpressionBuilder;

final class QueryBuilder
{
    /** @var ExpressionQueryBuilder */
    private $expressionBuilder;

    /**
     * QueryBuilder constructor.
     * @param StringTerm $term
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
     * @param string $term
     * @return static
     */
    public static function where(string $term): self
    {
        return new static(new StringTerm($term));
    }

    /**
     * @param string $term
     * @return $this
     */
    public function andWhere(string $term): self
    {
        $t = new StringTerm($term);
        $this->expressionBuilder->andWhere($t->getField(), $t->getTermOperator(), $t->getValue());

        return $this;
    }

    /**
     * @param string $term
     * @return $this
     */
    public function orWhere(string $term): self
    {
        $t = new StringTerm($term);
        $this->expressionBuilder->andWhere($t->getField(), $t->getTermOperator(), $t->getValue());

        return $this;
    }

    /**
     * @return Query
     */
    public function build(): Query
    {
        return $this->expressionBuilder->build();
    }
}
