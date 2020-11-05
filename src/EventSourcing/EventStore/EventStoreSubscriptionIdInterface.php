<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

/**
 * Contract for Event Store Subscription Identifiers.
 */
interface EventStoreSubscriptionIdInterface
{
    /**
     * Returns a string representation of this Event Store Subscription ID.
     */
    public function __toString(): string;

    /**
     * Constructs a new instance of this class from a string representation
     * of an Event Store Subscription ID.
     *
     * @return static
     */
    public static function fromString(string $identifier): self;

    /**
     * Indicates if this Event Store Subscription ID is equal to another one.
     */
    public function isEqualTo(self $identifier): bool;
}
