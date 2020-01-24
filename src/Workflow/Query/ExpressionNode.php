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
     * An Expression without a left node is considered an empty expression
     * @param ExpressionNode|null $left
     * @param ExpressionOperator|null $operator
     * @param ExpressionNode|null $right
     */
    public function __construct(?ExpressionNode $left = null, ?ExpressionOperator $operator = null, ?ExpressionNode $right = null)
    {
        if ($operator && !$right) {
            throw new InvalidArgumentException('An ExpressionNode cannot have an operator without a right node');
        }

        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }

    /**
     * @return ExpressionNode
     */
    public function getLeft(): ExpressionNode
    {
        return $this->left;
    }

    /**
     * @return ExpressionNode|null
     */
    public function getRight(): ?ExpressionNode
    {
        return $this->right;
    }

    /**
     * @return ExpressionOperator
     */
    public function getOperator(): ExpressionOperator
    {
        return $this->operator;
    }

    /**
     * Returns a string representation of this Expression
     * @return string
     */
    public function __toString()
    {
        $str = (string)$this->left;
        if ($this->operator) {
            $str = "($str) {$this->operator} ({$this->right})";
        }
        return $str;
    }
}
