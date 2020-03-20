<?php

namespace Tests\Morebec\Orkestra\EventSourcing;

use Morebec\Orkestra\EventSourcing\AbstractEventSourcedAggregateRoot;
use Morebec\Orkestra\Messaging\Event\EventInterface;
use Morebec\ValueObjects\Identity\UuidIdentifier;
use PHPUnit\Framework\TestCase;

class AbstractEventSourcedAggregateRootTest extends TestCase
{
    public function testMarkChangesAsCommitted(): void
    {
        $aggregate = TestAR::create();
        $this->assertNotEmpty($aggregate->getUncommittedChanges());
        $aggregate->markChangesAsCommitted();
        $this->assertEmpty($aggregate->getUncommittedChanges());
    }

    public function testGetVersion(): void
    {
        $aggregate = TestAR::create();
        $this->assertEquals(0, $aggregate->getVersion());
    }

    public function testGetUncommittedChanges(): void
    {
        $aggregate = TestAR::create();
        $this->assertCount(1, $aggregate->getUncommittedChanges());
        $aggregate->archive();
        $this->assertCount(2, $aggregate->getUncommittedChanges());
    }

    public function testLoadFromHistory(): void
    {
        $aggregate = TestAR::create();
        $aggregate->loadFromHistory([
            new TestARCreatedEvent('AR_ID'),
            new TestARArchivedEvent('AR_ID'),
        ]);

        $this->assertEquals(1, $aggregate->getVersion());
    }
}

final class TestAR extends AbstractEventSourcedAggregateRoot
{
    /** @var bool */
    private $archived;

    public static function create(): self
    {
        $self = new self();
        $self->recordChange(new TestARCreatedEvent(UuidIdentifier::generate()));

        return $self;
    }

    public function archive(): void
    {
        $this->recordChange(new TestARArchivedEvent($this->id));
    }

    public function isArchived(): bool
    {
        return $this->archived;
    }

    protected function applyChange(EventInterface $event): void
    {
        if ($event instanceof TestARCreatedEvent) {
            $this->id = $event->id;
        }

        if ($event instanceof TestARArchivedEvent) {
            $this->archived = true;
        }
    }
}

final class TestARCreatedEvent implements EventInterface
{
    /**
     * @var string
     */
    public $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}

final class TestARArchivedEvent implements EventInterface
{
    /**
     * @var string
     */
    public $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
