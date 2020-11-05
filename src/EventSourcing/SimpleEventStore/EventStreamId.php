<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use InvalidArgumentException;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamIdInterface;

/**
 * Represents the Stream ID of an event stream.
 */
class EventStreamId implements EventStreamIdInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * EventId constructor.
     */
    private function __construct(string $streamId)
    {
        if (!$streamId) {
            throw new InvalidArgumentException('An Event Stream ID cannot be null');
        }

        $this->value = $streamId;
    }

    /**
     * Returns a string representation of an Event Stream ID.
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Constructs a new instance of this class from a string representation
     * of an Event Stream ID.
     *
     * @return $this
     */
    public static function fromString(string $streamId): EventStreamIdInterface
    {
        return new static($streamId);
    }

    /**
     * Indicates if this Event Stream ID is equal to another one.
     */
    public function isEqualTo(EventStreamIdInterface $streamId): bool
    {
        if ($streamId instanceof self) {
            return $this->value === $streamId->value;
        }

        return (string) $this->value === (string) $streamId;
    }
}
