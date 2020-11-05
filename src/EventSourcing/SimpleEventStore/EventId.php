<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use InvalidArgumentException;
use Morebec\Orkestra\EventSourcing\EventStore\EventIdInterface;

/**
 * Represents the unique Identifier of an Event.
 */
class EventId implements EventIdInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * EventId constructor.
     */
    private function __construct(string $eventId)
    {
        if (!$eventId) {
            throw new InvalidArgumentException('An Event ID cannot be null');
        }

        $this->value = $eventId;
    }

    /**
     * Returns a string representation of an Event ID.
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Constructs a new instance of this class from a string representation
     * of an Event ID.
     *
     * @return $this
     */
    public static function fromString(string $eventId): EventIdInterface
    {
        return new static($eventId);
    }

    /**
     * Indicates if this Event ID is equal to another one.
     */
    public function isEqualTo(EventIdInterface $eventId): bool
    {
        if ($eventId instanceof self) {
            return $this->value === $eventId->value;
        }

        return (string) $this === (string) $eventId;
    }
}
