<?php

namespace Tests\Morebec\Orkestra\Workflow;

use Morebec\Orkestra\Workflow\WorkflowState;
use PHPUnit\Framework\TestCase;

class WorkflowStateTest extends TestCase
{
    public function test__construct(): void
    {
        $state = new WorkflowState('state_id', 'workflow_id');

        $this->assertEquals('state_id', $state->getId());
        $this->assertEquals('workflow_id', $state->getWorkflowId());
        $this->assertFalse($state->isCompleted());
    }

    public function testSet(): void
    {
        $state = new WorkflowState('state_id', 'workflow_id');
        $state->set('test_value', 'hello test');
        $this->assertEquals('hello test', $state->get('test_value'));
    }

    public function testMarkCompleted(): void
    {
        $state = new WorkflowState('state_id', 'workflow_id');
        $state->markCompleted();

        $this->assertTrue($state->isCompleted());
    }

    public function testFromArray(): void
    {
        $state = WorkflowState::fromArray([
            'id' => 'test_id',
            'workflow_id' => 'test_workflow_id',
            'completed' => true,
            'data' => [
                'key' => 'value'
            ]
        ]);

        $this->assertTrue($state->isCompleted());
        $this->assertEquals('test_id', $state->getId());
        $this->assertEquals('test_workflow_id', $state->getWorkflowId());
        $this->assertEquals('value', $state->get('key'));
    }

    public function testContainsKey(): void
    {
        $state = new WorkflowState('state_id', 'workflow_id');
        $state->set('test_value', 'hello test');
        $this->assertTrue($state->containsKey('test_value'));
    }

    public function testGet(): void
    {
        $state = new WorkflowState('state_id', 'workflow_id');
        $state->set('test_value', 'hello test');
        $this->assertEquals('hello test', $state->get('test_value'));

        $this->expectException(\InvalidArgumentException::class);
        $state->get('not-there');
    }
}
