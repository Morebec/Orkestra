<?php


namespace Morebec\Orkestra\Workflow;

use Morebec\Orkestra\Messaging\Event\EventSubscriberInterface;
use Morebec\Orkestra\Workflow\Query\ExpressionNode;
use Morebec\Orkestra\Workflow\Query\ExpressionOperator;
use Morebec\Orkestra\Workflow\Query\ExpressionQueryBuilder;
use Morebec\Orkestra\Workflow\Query\Query;
use Morebec\Orkestra\Workflow\Query\QueryBuilder;
use Morebec\Orkestra\Workflow\Query\StringTerm;
use Morebec\Orkestra\Workflow\Query\TermNode;
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
     * Updates a state in the repository
     * @param WorkflowState $state
     */
    protected function updateState(WorkflowState $state): void
    {
        $this->workflowStateRepository->update($state);
    }

    /**
     * Finds a state for this Workflow according to a query
     * @param Query $query
     * @return WorkflowState|null
     */
    protected function findOneStateWhere(Query $query): ?WorkflowState
    {
        $whereWorkflowId = new ExpressionNode(
            new TermNode('workflow_id', TermOperator::EQUAL(), $this->getId()),
            ExpressionOperator::AND(),
            $query->getExpressionNode()
        );

        $query = new Query($whereWorkflowId);
        return $this->workflowStateRepository->findOneBy($query);
    }

    /**
     * Finds an active state for this Workflow (i.e. non completed) according to a query
     * @param Query $query
     * @return WorkflowState|null
     */
    public function findOneActiveState(Query $query): ?WorkflowState
    {
        $andWhereNotCompleted = new ExpressionNode(
            new TermNode('completed', TermOperator::EQUAL(), false),
            ExpressionOperator::AND(),
            $query->getExpressionNode()
        );
        return $this->findOneStateWhere(new Query($andWhereNotCompleted));
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        $query = ExpressionQueryBuilder::where('workflow_id', TermOperator::EQUAL(), $this->getId())
                                        ->andWhere('completed', TermOperator::EQUAL(), false)
                                        ->build();
        $states = $this->workflowStateRepository->findBy($query);

        return count($states) !== 0;
    }
}
