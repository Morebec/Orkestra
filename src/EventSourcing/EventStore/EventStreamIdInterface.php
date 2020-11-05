<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

/**
 * Interface contract for the id of a stream.
 */
interface EventStreamIdInterface
{
    /**
     * Returns a string representation of an Event Stream ID.
     */
    public function __toString(): string;

    /**
     * Constructs a new instance of this class from a string representation
     * of an Event Stream ID.
     *
     * @return $this
     */
    public static function fromString(string $streamId): self;

    /**
     * Indicates if this Event Stream ID is equal to another one.
     */
    public function isEqualTo(self $streamId): bool;
}
