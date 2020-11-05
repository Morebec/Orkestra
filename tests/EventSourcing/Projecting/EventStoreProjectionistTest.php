<?php

namespace Tests\Morebec\Orkestra\EventSourcing\Projecting;

use Morebec\Orkestra\EventSourcing\EventStore\EventStoreInterface;
use Morebec\Orkestra\EventSourcing\Projecting\EventStoreProjectionist;
use Morebec\Orkestra\EventSourcing\Projecting\ProjectorInterface;
use Morebec\Orkestra\EventSourcing\Projecting\ProjectorStateStorageInterface;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\CatchupEventStoreSubscription;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\DomainEventDescriptor;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventId;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventStoreSubscriptionId;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventStreamId;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\StreamedEventCollection;
use Morebec\Orkestra\Messaging\Event\DomainEventInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class EventStoreProjectionistTest extends TestCase
{
    public function testReplayProjector(): void
    {
        $projectionist = $this->createProjectionist();
        $projector = $this->createProjector();

        $projectionist->replayProjector($projector);

        $this->assertTrue($projector->reset);
        $this->assertTrue($projector->booted);
        $this->assertTrue($projector->projected);
        $this->assertTrue($projector->shutdown);
    }

    public function testRunProjector(): void
    {
        $projectionist = $this->createProjectionist();
        $projector = $this->createProjector();

        $projectionist->runProjector($projector);

        $this->assertTrue($projector->booted);
        $this->assertTrue($projector->projected);
        $this->assertTrue($projector->shutdown);
    }

    public function testBootProjector(): void
    {
        $projectionist = $this->createProjectionist();
        $projector = $this->createProjector();

        $projectionist->bootProjector($projector);

        $this->assertTrue($projector->booted);
    }

    public function testShutdownProjector(): void
    {
        $projectionist = $this->createProjectionist();
        $projector = $this->createProjector();

        $projectionist->shutdownProjector($projector);

        $this->assertTrue($projector->shutdown);
    }

    public function testResetProjector(): void
    {
        $projectionist = $this->createProjectionist();
        $projector = $this->createProjector();

        $projectionist->resetProjector($projector);

        $this->assertTrue($projector->reset);
    }

    private function createProjectionist(): EventStoreProjectionist
    {
        $eventStore = $this->getMockBuilder(EventStoreInterface::class)->getMock();
        $eventStore->method('readStreamForward')->willReturn(
            new StreamedEventCollection(EventStreamId::fromString('*'), [
                DomainEventDescriptor::forDomainEvent(EventId::fromString('event_id'), $this->createEvent()),
            ])
        );

        $eventStore->method('getSubscription')->willReturn(new CatchupEventStoreSubscription(
            EventStoreSubscriptionId::fromString('sub'),
            EventStreamId::fromString('stream')
        ));

        $projectorStateStorage = $this->getMockBuilder(ProjectorStateStorageInterface::class)->getMock();

        /* @var EventStoreInterface $eventStore */
        /* @var ProjectorStateStorageInterface $projectorStateStorage */
        return new EventStoreProjectionist($eventStore, $projectorStateStorage, new NullLogger());
    }

    private function createEvent(): DomainEventInterface
    {
        return new class() implements DomainEventInterface {
            public static function getTypeName(): string
            {
                return 'domain_event';
            }
        };
    }

    private function createProjector(): ProjectorInterface
    {
        return new class() implements ProjectorInterface {
            public $booted = false;
            public $projected = false;
            public $shutdown = false;
            public $reset = false;

            public function boot(): void
            {
                $this->booted = true;
            }

            public function project(DomainEventInterface $event): void
            {
                $this->projected = true;
            }

            public function shutdown(): void
            {
                $this->shutdown = true;
            }

            public function reset(): void
            {
                $this->reset = true;
            }

            public static function getTypeName(): string
            {
                return 'test_projector';
            }
        };
    }
}
