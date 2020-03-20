<?php

namespace Morebec\Orkestra\Workflow\Query;

use Exception;
use InvalidArgumentException;
use LogicException;

final class Term
{
    /** @var string */
    private $field;

    /** @var TermOperator */
    private $operator;

    /** @var mixed */
    private $value;

    /**
     * TermNode constructor.
     *
     * @param mixed $value
     */
    public function __construct(string $field, TermOperator $operator, $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;

        // Validate Operator and value combination
        if ($operator->isEqualTo(TermOperator::IN()) || $operator->isEqualTo(TermOperator::NOT_IN())) {
            if (!\is_array($value)) {
                throw new InvalidArgumentException("The right operand must be an array when used with operator {$operator}");
            }
        }
    }

    public function __toString()
    {
        try {
            $value = $this->stringifyValue($this->value);
        } catch (Exception $e) {
            return 'INVALID EXPRESSION';
        }

        return "{$this->field} {$this->operator} {$value}";
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getOperator(): TermOperator
    {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Indicates if a value matches this term.
     *
     * @param mixed $value value to test
     */
    public function matches($value): bool
    {
        switch ($this->operator) {
            case TermOperator::EQUAL:
                return $value === $this->value;
            case TermOperator::NOT_EQUAL:
                return $value !== $this->value;
            case TermOperator::LESS_THAN:
                return $value < $this->value;
            case TermOperator::GREATER_THAN:
                return $value > $this->value;
            case TermOperator::LESS_OR_EQUAL:
                return $value <= $this->value;
            case TermOperator::GREATER_OR_EQUAL:
                return $value >= $this->value;
            case TermOperator::IN:
                return \in_array($value, $this->value, true);
            case TermOperator::NOT_IN:
                return !\in_array($value, $this->value, true);
            case TermOperator::CONTAINS:
                return \in_array($this->value, $value, true);
            case TermOperator::NOT_CONTAINS:
                return !\in_array($this->value, $value, true);
        }

        throw new LogicException("Unsupported Operator {$this->operator}");
    }

    /**
     * @param mixed $value
     *
     * @throws Exception
     */
    private function stringifyValue($value): string
    {
        $str = json_encode($value);
        if (!$str) {
            throw new Exception('Could not convert expression to string');
        }

        return $str;
    }
}
