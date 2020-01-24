<?php

namespace Tests\Morebec\Orkestra\Workflow;

use Morebec\Orkestra\Messaging\Event\EventInterface;
use Morebec\Orkestra\Workflow\AbstractWorkflow;
use Morebec\Orkestra\Workflow\InMemoryWorkflowStateRepository;
use Morebec\Orkestra\Workflow\Query\QueryBuilder;
use Morebec\Orkestra\Workflow\WorkflowState;
use Morebec\ValueObjects\Identity\UuidIdentifier;
use PHPUnit\Framework\TestCase;

class AbstractWorkflowTest extends TestCase
{
    public function testStart()
    {
        $workflow = new TestWorkflow(new InMemoryWorkflowStateRepository());

        $userId = UuidIdentifier::generate();
        $event = new TestWorkflowStarted($userId);
        $workflow->onTestWorkflowStarted($event);

        $workflow->onStep1Completed(new Step1Completed($userId));
        $this->assertTrue($workflow->isActive());

        $workflow->onStep2Completed(new Step2Completed($userId));
        $this->assertTrue($workflow->isActive());

        $workflow->onStep3Completed(new Step3Completed($userId));
        $this->assertFalse($workflow->isActive());
    }
}

/**
 * Class TestWorkflow
 * This workflow goes through 3 steps for a given user
 */
class TestWorkflow extends AbstractWorkflow
{
    private const ID = 'test_workflow';

    /**
     * @inheritDoc
     */
    protected function initializeState(WorkflowState $state): void
    {
        $state->set('current_step', 1);
    }

    /**
     * Entry point
     * @param TestWorkflowStarted $event
     */
    public function onTestWorkflowStarted(TestWorkflowStarted $event): void
    {
        $state = $this->start();
        $state->set('user_id', $event->userId);
        $this->updateState($state);
    }

    /**
     * @param Step1Completed $event
     */
    public function onStep1Completed(Step1Completed $event): void
    {
        $state = $this->findStateForUserAtStep($event->userId, 1);
        if (!$state) {
            return; // Nothing to do
        }

        $state->set('current_step', 2);
        $this->updateState($state);
    }

    public function onStep2Completed(Step2Completed $event): void
    {
        $state = $this->findStateForUserAtStep($event->userId, 2);
        if (!$state) {
            return; // Nothing to do
        }

        $state->set('current_step', 3);
        $this->updateState($state);
    }

    public function onStep3Completed(Step3Completed $event): void
    {
        $state = $this->findStateForUserAtStep($event->userId, 3);
        if (!$state) {
            return; // Nothing to do
        }

        $state->markCompleted();
        $this->updateState($state);
    }

    /**
     * Finds a state for a given user at a given step
     * @param string $userId
     * @param int $step
     * @return WorkflowState|null
     */
    private function findStateForUserAtStep(string $userId, int $step): ?WorkflowState
    {
        $query = QueryBuilder::where("current_step === {$step}")->andWhere("user_id === {$userId}")
            ->build();
        return $this->findOneActiveState($query);
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            TestWorkflowStarted::class => 'onTestWorkflowStarted',
            Step1Completed::class => 'onStep1Completed',
            Step2Completed::class => 'onStep2Completed',
            Step3Completed::class => 'onStep3Completed'
        ];
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return self::ID;
    }
}

class TestWorkflowEvent implements EventInterface
{
    /** @var string */
    public $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }
}

class TestWorkflowStarted extends TestWorkflowEvent
{
}
class Step1Completed extends TestWorkflowEvent
{
}
class Step2Completed extends TestWorkflowEvent
{
}
class Step3Completed extends TestWorkflowEvent
{
}
