<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\EventStore\EventIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreSubscriptionIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreSubscriptionInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\RecordedEventDescriptorInterface;

/**
 * Interface contract for a unit capable of writing events
 * to a persistence storage.
 */
interface SimpleEventStorageWriterInterface
{
    /**
     * Creates a Stream in the storage.
     */
    public function createStream(EventStream $stream): void;

    /**
     * Appends a list of Recorded Events to the storage in a atomic way.
     *
     * @param RecordedEventDescriptorInterface[] $recordedEvents
     */
    public function appendToStream(EventStreamIdInterface $streamId, iterable $recordedEvents): void;

    /**
     * Adds a subscription to the event store storage.
     *
     * @return mixed
     */
    public function startSubscription(EventStoreSubscriptionInterface $subscription);

    /**
     * Cancels a Subscription  in the storage.
     */
    public function cancelSubscription(EventStoreSubscriptionIdInterface $subscriptionId): void;

    /**
     * Resets a catchup subscription.
     */
    public function resetSubscription(EventStoreSubscriptionIdInterface $subscriptionId): void;

    /**
     * Changes the last read event of a catchup subscription.
     */
    public function advanceSubscription(EventStoreSubscriptionIdInterface $subscriptionId, EventIdInterface $eventId): void;
}
