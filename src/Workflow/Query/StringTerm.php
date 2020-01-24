<?php


namespace Morebec\Orkestra\Workflow\Query;

/**
 * Terms expressed in strings instead
 */
class StringTerm extends TermNode
{
    public function __construct(string $expression)
    {
        $expression = $this->sanitize($expression);
        $units = explode(' ', $expression, 3);
        if (count($units) < 3) {
            throw new \InvalidArgumentException("Expression $expression is invalid");
        }
        [$field, $operator, $value] = $units;
        $value = $this->unstringifyValue($value);
        $operator = new TermOperator($operator);
        parent::__construct($field, $operator, $value);
    }

    /**
     * Sanitizes a string before parsing
     * @param string $expression
     * @return string
     */
    private function sanitize(string $expression): string
    {
        $expression = trim($expression);
        $expression = preg_replace('/\s+/', ' ', $expression);

        return $expression;
    }

    /**
     * @param string $value
     * @return mixed
     */
    private function unstringifyValue(string $value)
    {
        return json_decode($value);
    }
}
