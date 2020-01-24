<?php


namespace Morebec\Orkestra\Workflow;

/**
 * Interface WorkflowInterface
 * A Workflow is sequence of steps/transactions to allow communication between different services/modules or bounded context.
 * Each step in a Workflow is executed as a consequence of the successful completion of the previous step.
 * It allows to implement compensation behaviour in case of a step failure. The Workflow knows how to undo the changes
 * it has performed.
 * A Workflow is implemented as a state machine tailored for Commands and Events.
 * A workflow has a state, that contains all the necesasry data to track the progression in terms of steps.
 * For each execution of a Workflow a corresponding WorkflowState object is created and persisted.
 */
interface WorkflowInterface
{
    /**
     * Returns the Id of this workflow. All instances of the same workflow share this id.
     * This is used to associate a Workflow State with a specific Workflow
     * @return string
     */
    public function getId(): string;

    /**
     * Starts the Workflow and returns the created state for this started execution
     * @return WorkflowState
     */
    public function start(): WorkflowState;

    /**
     * Indicates if there is currently at least one state not completed for this workflow
     * @return bool
     */
    public function isActive(): bool;
}