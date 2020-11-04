<?php

namespace Morebec\Orkestra\Workflow\Query;

use InvalidArgumentException;

class ExpressionNode
{
    /** @var ExpressionNode|null */
    private $left;

    /** @var ExpressionNode|null */
    private $right;

    /** @var ExpressionOperator|null */
    private $operator;

    /**
     * ExpressionNode constructor.
     * An Expression without a left node is considered an empty expression.
     */
    public function __construct(?self $left = null, ?ExpressionOperator $operator = null, ?self $right = null)
    {
        if ($operator && !$right) {
            throw new InvalidArgumentException('An ExpressionNode cannot have an operator without a right node');
        }

        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }

    /**
     * Returns a string representation of this Expression.
     *
     * @return string
     */
    public function __toString()
    {
        $str = (string) $this->left;
        if ($this->operator) {
            $str = "($str) {$this->operator} ({$this->right})";
        }

        return $str;
    }

    public function getLeft(): self
    {
        return $this->left;
    }

    public function getRight(): ?self
    {
        return $this->right;
    }

    public function getOperator(): ?ExpressionOperator
    {
        return $this->operator;
    }
}
