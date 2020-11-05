<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

/**
 * Interface for Types of Events.
 */
interface EventTypeInterface
{
    /**
     * Returns a string representation of an Event Type.
     */
    public function __toString(): string;

    /**
     * Constructs a new instance of this class from a string representation
     * of an event Type.
     *
     * @return $this
     */
    public static function fromString(string $eventId): self;

    /**
     * Indicates if this event Type is equal to another one.
     */
    public function isEqualTo(self $eventId): bool;
}
