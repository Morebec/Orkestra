<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\EventStore\EventIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreSubscriptionIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreSubscriptionInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamInterface;
use Morebec\Orkestra\EventSourcing\EventStore\StreamedEventCollectionInterface;
use Morebec\Orkestra\EventSourcing\EventStore\StreamNotFoundException;

/**
 * Interface contract for a service unit capable or reading events from a persistence storage
 * as written by a EventWriterInterface of the same type.
 */
interface SimpleEventStorageReaderInterface
{
    /**
     * Reads a stream of events starting at a given event and forward.
     * The starting point will not be included in the returned collection.
     * Or reads the entire stream if the eventId provided is null.
     * If the stream does not exists, throws an exception.
     *
     * @throws StreamNotFoundException
     */
    public function readStreamForward(EventStreamIdInterface $streamId, ?EventIdInterface $eventId = null, int $limit = 0): StreamedEventCollectionInterface;

    /**
     * Reads a stream of events starting at a given event and backward.
     * The starting point will not be included in the returned collection.
     * Or reads the entire stream if the eventId provided is null.
     * If the stream does not exists, throws an exception.
     *
     * @throws StreamNotFoundException
     */
    public function readStreamBackward(EventStreamIdInterface $streamId, ?EventIdInterface $eventId = null, int $limit = 0): StreamedEventCollectionInterface;

    /**
     * Reads information about a given Event Stream or throws an exception if it was not found.
     */
    public function getStream(EventStreamIdInterface $streamId): ?EventStreamInterface;

    /**
     * Returs a subscription in this event store or null if it was not found.
     */
    public function getSubscription(EventStoreSubscriptionIdInterface $subscriptionId): ?EventStoreSubscriptionInterface;
}
