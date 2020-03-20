<?php

namespace Morebec\Orkestra\EventSourcing;

use Morebec\DateTime\SystemClock;
use Morebec\Orkestra\EventSourcing\EventStore\EventDescriptor;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreInterface;
use Morebec\Orkestra\Messaging\Event\Event;
use Morebec\Orkestra\Messaging\Event\EventInterface;
use Morebec\Orkestra\Modeling\AggregateRootIdentifierInterface;

class AggregateRootEventStore implements AggregateRootEventStoreInterface
{
    /**
     * @var EventStoreInterface
     */
    private $eventStore;

    /**
     * {@inheritdoc}
     */
    public function __construct(EventStoreInterface $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * {@inheritdoc}
     */
    public function saveEvents(AggregateRootIdentifierInterface $id, array $changes, int $expectedVersion): void
    {
        $events = array_map(static function (EventInterface $event) use ($id, $expectedVersion) {
            return new EventDescriptor(
                "evt_{s$id}@{$expectedVersion}",
                $event,
                $event instanceof Event ? $event->occurredAt : SystemClock::now()
            );
        }, $changes);
        $this->eventStore->appendToStream($id, $expectedVersion, $events);
    }

    /**
     * {@inheritdoc}
     */
    public function findAggregateRootVersion(AggregateRootIdentifierInterface $identifier): int
    {
        return $this->eventStore->findEventStreamVersion($identifier);
    }

    /**
     * {@inheritdoc}
     */
    public function findEventsForAggregate(AggregateRootIdentifierInterface $identifier): array
    {
        return $this->eventStore->readStreamFromStartForward($identifier);
    }

    /**
     * {@inheritdoc}
     */
    public function replayFromEventId(string $eventId): array
    {
        return $this->eventStore->readAllFromEventIdForward($eventId);
    }

    /**
     * {@inheritdoc}
     */
    public function replayFromTimestamp(float $timestamp): array
    {
        return $this->eventStore->readAllFromTimestampForward($timestamp);
    }

    /**
     * {@inheritdoc}
     */
    public function findLatestEvent(): ?EventDescriptor
    {
        return $this->eventStore->readLatest();
    }

    /**
     * @return EventStoreInterface
    /**
     * {@inheritdoc}
     */
    public function getEventStore(): EventStoreInterface
    {
        return $this->eventStore;
    }
}
