<?php

namespace Morebec\Orkestra\EventSourcing;

use Morebec\Orkestra\EventSourcing\EventStore\EventDescriptor;
use Morebec\Orkestra\Messaging\Event\EventInterface;
use Morebec\Orkestra\Modeling\AggregateRootIdentifierInterface;
use Morebec\Orkestra\Modeling\AggregateRootNotFoundException;

interface AggregateRootEventStoreInterface
{
    /**
     * Saves events for a given aggregate.
     *
     * @param AggregateRootIdentifierInterface $id              aggregate id
     * @param array<EventInterface>            $changes         list of events describing the changes to the aggregate
     * @param int                              $expectedVersion current version of the aggregate in the store
     *
     * @throws AggregateRootVersionMismatchException when the expected version does not match what's in store
     */
    public function saveEvents(AggregateRootIdentifierInterface $id, array $changes, int $expectedVersion): void;

    /**
     * Returns the stream of events of an aggregate root with a given id.
     *
     * @return array<AggregateEventDescriptor>
     */
    public function findEventsForAggregate(AggregateRootIdentifierInterface $identifier): array;

    /**
     * Returns the version of an aggregate root in the store.
     *
     * @throws AggregateRootNotFoundException
     */
    public function findAggregateRootVersion(AggregateRootIdentifierInterface $identifier): int;

    /**
     * Returns all events that occurred after the specified event with given id.
     * If the event cannot be found in the store, will return an empty list.
     *
     * @return array<AggregateEventDescriptor>
     */
    public function replayFromEventId(string $eventId): array;

    /**
     * Returns all events *after* a given timestamp in milliseconds.
     * Meaning if a the occurrence data of the event matches the provided timestamp exactly,
     * this event will not be returned in the list.
     * To replay from the beginning, simply provide 0 as the time stamp.
     *
     * @return array<AggregateEventDescriptor>
     */
    public function replayFromTimestamp(float $timestamp): array;

    /**
     * Returns the latest event in the store.
     * This can be used by event handlers, workflows and projectors to ensure they are correctly synchronised,
     * with the event store.
     *
     * @return AggregateEventDescriptor|null
     */
    public function findLatestEvent(): ?EventDescriptor;
}
