<?php


namespace Morebec\Orkestra\Workflow\Query;

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
     * @param string $field
     * @param TermOperator $operator
     * @param mixed $value
     */
    public function __construct(string $field, TermOperator $operator, $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;

        // Validate Operator and value combination
        if ($operator->isEqualTo(TermOperator::IN()) || $operator->isEqualTo(TermOperator::NOT_IN())) {
            if (!is_array($value)) {
                throw new \InvalidArgumentException("The right operand must be an array when used with operator {$operator}");
            }
        }
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return TermOperator
     */
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
     * Indicates if a value matches this term
     * @param mixed $value value to test
     * @return bool
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
                return in_array($value, $this->value, true);
            case TermOperator::NOT_IN:
                return !in_array($value, $this->value, true);
            case TermOperator::CONTAINS:
                return in_array($this->value, $value, true);
            case TermOperator::NOT_CONTAINS:
                return !in_array($this->value, $value, true);
        }

        throw new LogicException("Unsupported Operator {$this->operator}");
    }

    public function __toString()
    {
        $value = $this->stringifyValue($this->value);

        return "{$this->field} {$this->operator} {$value}";
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function stringifyValue($value): string
    {
        if (is_array($value)) {
            $self = $this;
            $values = array_map(static function ($v) use ($self) {
                return $self->stringifyValue($v);
            }, $value);
            return sprintf('[%s]', implode(', ', $values));
        }

        if (is_string($value)) {
            return "'$value'";
        }

        if (is_bool($value)) {
            return $value === true ? 'true' : 'false';
        }
        return (string)$value;
    }
}
