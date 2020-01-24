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
     * @param Query $query
     * @return mixed
     */
    public function findBy(Query $query);
}
