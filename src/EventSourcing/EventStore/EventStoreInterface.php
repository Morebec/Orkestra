<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

/**
 * Represents a Generic interface for working with an event store.
 * An event store is a simple store managing events in an orderly fashion.
 * They are ordered by order of insertion.
 * The two basic requirements for the event store are:
 * - Appending events to a stream of events.
 * - Reading events back from stream in write order.
 * - Protecting against concurrency issues using optimistic concurrency with the use of a stream version.
 */
interface EventStoreInterface
{
    /**
     * Appends events to a given stream.
     * If the stream does not exist, it will get implicitly created.
     * It takes a stream version parameter to detect if there has been any concurrent appends to
     * this stream, to enforce consistency boundaries when required.
     * This parameter can be null in cases where this consistency check is unnecessary.
     *
     * @param EventDescriptorInterface[] $eventDescriptors
     *
     *@throws ConcurrencyException
     */
    public function appendToStream(
        EventStreamIdInterface $streamId,
        iterable $eventDescriptors,
        ?EventStreamVersionInterface $expectedStreamVersion = null
    ): void;

    /**
     * Reads an event stream and forward starting at a given event with ID until the end.
     * The starting event will not be included in the resulting streamed event collection.
     * If the starting event is null, will start from the beginning of the stream.
     *
     * @param int $limit The maximum number of events to be returned. Can be used for batching. a limit of 0 should be used to indicate no limit.
     *
     * @throws StreamNotFoundException
     */
    public function readStreamForward(EventStreamIdInterface $streamId, ?EventIdInterface $eventId = null, int $limit = 0): StreamedEventCollectionInterface;

    /**
     * Reads an event stream and forward starting at a given event with ID until the end.
     * The starting event will not be included in the resulting streamed event collection.
     * If the starting event is null, will start from the beginning of the stream.
     *
     * @param int $limit The maximum number of events to be returned. Can be used for batching. a limit of 0 should be used to indicate no limit.
     *
     * @throws StreamNotFoundException
     */
    public function readStreamBackward(EventStreamIdInterface $streamId, ?EventIdInterface $eventId = null, int $limit = 0): StreamedEventCollectionInterface;

    /**
     * Returns an event stream's information or null if the stream does not exist.
     *
     * @return ?EventStreamInterface
     */
    public function getStream(EventStreamIdInterface $streamId): ?EventStreamInterface;

    /**
     * Returns a subscription by its ID or null if it was not found.
     */
    public function getSubscription(EventStoreSubscriptionIdInterface $subscriptionId): ?EventStoreSubscriptionInterface;

    /**
     * Adds a Subscription to this event store.
     */
    public function startSubscription(EventStoreSubscriptionInterface $subscription): void;

    /**
     * Cancels a subscription in this event store, removing it from the store.
     */
    public function cancelSubscription(EventStoreSubscriptionIdInterface $subscriptionId): void;

    /**
     * Resets a Catchup subscription, so that it is considered not having ready any event.
     */
    public function resetSubscription(EventStoreSubscriptionIdInterface $subscriptionId): void;

    /**
     * Advances a Catchup subscription, so that it is considered that have last read an event.
     */
    public function advanceSubscription(EventStoreSubscriptionIdInterface $subscriptionId, EventIdInterface $eventId): void;
}
