<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

/**
 * Represents a generic subscription to the event store by a service or piece of logic.
 * There can be multiple types of subscriptions:
 * - Persistent Subscriptions -> Actively listening to the event's store changes in realtime.
 * - Catchup Subscriptions -> Listens to specific streams and tracking progress at a later time.
 */
interface EventStoreSubscriptionInterface
{
    /**
     * Returns the ID of this subscription.
     */
    public function getId(): EventStoreSubscriptionIdInterface;

    /**
     * Returns the concerned stream ID of this subscription.
     */
    public function getStreamId(): EventStreamIdInterface;

    /**
     * Returns the list of events this subscription listens to.
     * It works as a filter, meaning that if this array is empty,
     * this subscription will be considered as interested by all events.
     */
    public function getTypeFilter(): array;
}
