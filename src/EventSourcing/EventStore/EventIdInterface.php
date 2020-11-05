<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

/**
 * Interface contract for Event Identifiers.
 */
interface EventIdInterface
{
    /**
     * Returns a string representation of an Event ID.
     */
    public function __toString(): string;

    /**
     * Constructs a new instance of this class from a string representation
     * of an Event ID.
     */
    public static function fromString(string $eventId): self;

    /**
     * Indicates if this Event ID is equal to another one.
     */
    public function isEqualTo(self $eventId): bool;
}
