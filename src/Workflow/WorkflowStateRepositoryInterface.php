<?php

namespace Morebec\Orkestra\Workflow;

use Morebec\Orkestra\Workflow\Query\Query;

/**
 * Repository used to store the progress/state of workflows.
 */
interface WorkflowStateRepositoryInterface
{
    /**
     * Adds a new State to this repository.
     */
    public function add(WorkflowState $state): void;

    /**
     * Updates a state in the repository.
     */
    public function update(WorkflowState $state): void;

    /**
     * Removes a state from this repository.
     */
    public function remove(WorkflowState $state): void;

    /**
     * Finds one WorkflowState by its id.
     *
     * @return WorkflowState
     */
    public function findById(string $id): ?WorkflowState;

    /**
     * Finds states by their workflow id.
     *
     * @return array<WorkflowState>
     */
    public function findByWorkflowId(string $workflowId): array;

    /**
     * @return array<WorkflowState>
     */
    public function findBy(Query $query): array;

    /**
     * Find one state by a given query or null if none matched.
     */
    public function findOneBy(Query $query): ?WorkflowState;
}
