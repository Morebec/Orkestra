<?php


namespace Morebec\Orkestra\Workflow;

use Morebec\Orkestra\Workflow\Query\Query;

interface WorkflowStateRepositoryInterface
{
    /**
     * Adds a new State to this repository
     * @param WorkflowState $state
     */
    public function add(WorkflowState $state): void;

    /**
     * Updates a state in the repository
     * @param WorkflowState $state
     */
    public function update(WorkflowState $state): void;

    /**
     * Removes a state from this repository
     * @param WorkflowState $state
     */
    public function remove(WorkflowState $state): void;


    /**
     * Finds one WorkflowState by its id
     * @param string $id
     * @return WorkflowState
     */
    public function findById(string $id): ?WorkflowState;

    /**
     * Finds states by their workflow id
     * @param string $workflowId
     * @return array<WorkflowState>
     */
    public function findByWorkflowId(string $workflowId): array;

    /**
     * @param Query $query
     * @return array<WorkflowState>
     */
    public function findBy(Query $query): array;

    /**
     * Find one state by a given query or null if none matched
     * @param Query $query
     * @return WorkflowState|null
     */
    public function findOneBy(Query $query): ?WorkflowState;
}
