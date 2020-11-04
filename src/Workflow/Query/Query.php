<?php

namespace Morebec\Orkestra\Workflow\Query;

final class Query
{
    /**
     * @var ExpressionNode
     */
    private $expressionNode;

    public function __construct(ExpressionNode $expressionNode)
    {
        $this->expressionNode = $expressionNode;
    }

    public function __toString()
    {
        return (string) $this->expressionNode;
    }

    public function getExpressionNode(): ExpressionNode
    {
        return $this->expressionNode;
    }
}
