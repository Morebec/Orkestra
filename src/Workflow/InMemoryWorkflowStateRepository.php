<?php

namespace Morebec\Orkestra\Workflow;

use Morebec\Collections\HashMap;
use Morebec\Orkestra\Workflow\Query\ExpressionNode;
use Morebec\Orkestra\Workflow\Query\ExpressionOperator;
use Morebec\Orkestra\Workflow\Query\Query;
use Morebec\Orkestra\Workflow\Query\QueryBuilder;
use Morebec\Orkestra\Workflow\Query\TermNode;

/**
 * In memory implementation of a Workflow state repository.
 */
final class InMemoryWorkflowStateRepository implements WorkflowStateRepositoryInterface
{
    /** @var HashMap<string, array> */
    private $states;

    public function __construct()
    {
        $this->states = new HashMap();
    }

    /**
     * {@inheritdoc}
     */
    public function add(WorkflowState $state): void
    {
        $this->states->put($state->getId(), $state->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function update(WorkflowState $state): void
    {
        $this->states->put($state->getId(), $state->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function remove(WorkflowState $state): void
    {
        $this->states->remove($state->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function findBy(Query $query): array
    {
        $matches = [];
        foreach ($this->states as $state) {
            if ($this->matchesQueryForRecord($query, $state)) {
                $matches[] = WorkflowState::fromArray($state);
            }
        }

        return $matches;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(Query $query): ?WorkflowState
    {
        foreach ($this->states as $state) {
            if ($this->matchesQueryForRecord($query, $state)) {
                return WorkflowState::fromArray($state);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function findById(string $id): ?WorkflowState
    {
        return $this->findOneBy(QueryBuilder::where("id === $id")->build());
    }

    /**
     * {@inheritdoc}
     */
    public function findByWorkflowId(string $workflowId): array
    {
        return $this->findBy(QueryBuilder::where("workspace_id === $workflowId")->build());
    }

    /**
     * Indicates if the given query matches a record.
     *
     * @param array<string, mixed> $record
     */
    private function matchesQueryForRecord(Query $query, array $record): bool
    {
        return $this->matchesExpressionForRecord($query->getExpressionNode(), $record);
    }

    /**
     * Indicates if the given query matches a record.
     *
     * @param array<string, mixed> $record
     */
    private function matchesExpressionForRecord(ExpressionNode $node, array $record): bool
    {
        if ($node instanceof TermNode) {
            $field = $node->getField();
            $source = $record;
            if (!\in_array($field, ['workflow_id', 'id', 'completed'])) {
                $source = $record['data'];
            }
            if (!\array_key_exists($field, $source)) {
                return false;
            }
            $value = $source[$field];

            return $node->matches($value);
        }

        $leftNode = $node->getLeft();
        $leftValue = $this->matchesExpressionForRecord($leftNode, $record);

        $operator = $node->getOperator();
        if (!$operator) {
            return $leftValue;
        }

        /** @var ExpressionNode $rightNode */
        $rightNode = $node->getRight();
        $rightValue = $this->matchesExpressionForRecord($rightNode, $record);

        return $this->evaluateOperator($leftValue, $operator, $rightValue);
    }

    /**
     * Evaluates a right and left value with a logical operator.
     */
    private function evaluateOperator(bool $leftValue, ExpressionOperator $operator, bool $rightValue): bool
    {
        if ($rightValue === $leftValue) {
            return $rightValue;
        }

        if ($operator->isEqualTo(ExpressionOperator::AND())) {
            return $rightValue && $leftValue;
        }

        if ($operator->isEqualTo(ExpressionOperator::OR())) {
            return $rightValue || $leftValue;
        }

        throw new \InvalidArgumentException("Unsupported Operator '$operator'");
    }
}
