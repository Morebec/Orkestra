<?php

namespace Tests\Morebec\Orkestra\Workflow;

use Morebec\Orkestra\Workflow\InMemoryWorkflowStateRepository;
use Morebec\Orkestra\Workflow\Query\QueryBuilder;
use Morebec\Orkestra\Workflow\WorkflowState;
use Morebec\ValueObjects\Identity\UuidIdentifier;
use PHPUnit\Framework\TestCase;

class InMemoryWorkflowStateRepositoryTest extends TestCase
{
    public function testFindBy(): void
    {
        $repo = new InMemoryWorkflowStateRepository();
        $id = UuidIdentifier::generate();
        $state = new WorkflowState($id, 'test_workflow');
        $repo->add($state);

        $ret = $repo->findById($id);
        $this->assertEquals($state, $ret);

        $state->set('key_data', 'value');
        $repo->update($state);

        // Find by workspace id
        $ret = $repo->findOneBy(QueryBuilder::where('workflow_id === test_workflow')->build());
        $this->assertNotNull($ret);
        $this->assertEquals($state, $ret);

        // Find by data
        $ret = $repo->findOneBy(QueryBuilder::where('key_data === value')->build());
        $this->assertNotNull($ret);
        $this->assertEquals($state, $ret);
    }

    public function testAdd(): void
    {
        $repo = new InMemoryWorkflowStateRepository();
        $id = UuidIdentifier::generate();
        $state = new WorkflowState($id, 'test_workflow');
        $repo->add($state);

        $ret = $repo->findById($id);
        $this->assertNotNull($ret);
        $this->assertEquals($state, $ret);
    }

    public function testUpdate(): void
    {
        $repo = new InMemoryWorkflowStateRepository();
        $id = UuidIdentifier::generate();
        $state = new WorkflowState($id, 'test_workflow');
        $repo->add($state);

        $state->set('new_key', 'value');
        $repo->update($state);
        $ret = $repo->findById($id);
        $this->assertTrue($ret->containsKey('new_key'));
    }

    public function testRemove(): void
    {
        $repo = new InMemoryWorkflowStateRepository();
        $id = UuidIdentifier::generate();
        $state = new WorkflowState($id, 'test_workflow');
        $repo->add($state);
        $repo->remove($state);
        $ret = $repo->findById($id);
        $this->assertNull($ret);
    }
}
