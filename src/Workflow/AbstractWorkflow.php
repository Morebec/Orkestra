<?php


namespace Morebec\Orkestra\Workflow;

use Morebec\Orkestra\Messaging\Event\EventSubscriberInterface;
use Morebec\Orkestra\Workflow\Query\QueryBuilder;
use Morebec\Orkestra\Workflow\Query\TermOperator;
use Morebec\ValueObjects\Identity\UuidIdentifier;

abstract class AbstractWorkflow implements WorkflowInterface, EventSubscriberInterface
{
    /**
     * @var WorkflowStateRepositoryInterface
     */
    protected $workflowStateRepository;

    public function __construct(WorkflowStateRepositoryInterface $workflowStateRepository)
    {
        $this->workflowStateRepository = $workflowStateRepository;
    }

    /**
     * @inheritDoc
     */
    public function start(): WorkflowState
    {
        // Create a state
        $state = new WorkflowState(UuidIdentifier::generate(), $this->getId());
        // Initialize State in subclass
        $this->initializeState($state);
        // Add the state in repository
        $this->workflowStateRepository->add($state);

        return $state;
    }

    /**
     * Initialize a state at the start of a workflow
     * @param WorkflowState $state
     */
    abstract protected function initializeState(WorkflowState $state): void;

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        $states = $this->workflowStateRepository->findBy(
            QueryBuilder::where('workflow_id', TermOperator::EQUAL(), $this->getId())
                            ->andWhere('completed', TermOperator::EQUAL(), false)->build()
        );

        return count($states) !== 0;
    }
}
