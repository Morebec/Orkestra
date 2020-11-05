<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use InvalidArgumentException;
use Morebec\Orkestra\EventSourcing\EventStore\EventTypeInterface;

/**
 * Represents the type of an event.
 */
class EventType implements EventTypeInterface
{
    /**
     * @var string
     */
    private $value;

    /**
     * EventType constructor.
     */
    private function __construct(string $eventType)
    {
        if (!$eventType) {
            throw new InvalidArgumentException('An Event Type cannot be null');
        }

        $this->value = $eventType;
    }

    /**
     * Returns a string representation of an Event Type.
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Constructs a new instance of this class from a string representation
     * of an event Type.
     *
     * @return $this
     */
    public static function fromString(string $eventId): EventTypeInterface
    {
        return new static($eventId);
    }

    /**
     * Indicates if this event Type is equal to another one.
     */
    public function isEqualTo(EventTypeInterface $eventId): bool
    {
        if ($eventId instanceof self) {
            return $this->value === $eventId->value;
        }

        return (string) $this === (string) $eventId;
    }
}
