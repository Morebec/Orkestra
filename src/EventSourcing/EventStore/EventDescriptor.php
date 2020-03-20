<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

use Morebec\DateTime\DateTime;
use Morebec\Orkestra\Messaging\Event\EventInterface;

/**
 * An Event descriptor represents an an event to be stored.
 * It contains metadata about an event in order to describe it in the event store.
 * It can be seen as an envelope.
 */
class EventDescriptor
{
    /**
     * @var string id to uniquely represent the payload
     */
    public $eventId;

    /**
     * @var EventInterface event
     */
    public $payload;

    /**
     * DateTime with milliseconds precision for the time at which the event occurred.
     *
     * @var DateTime
     */
    public $occurredAt;

    /**
     * Friendly name of the type of the event.
     *
     * @var string
     */
    public $eventType;

    public function __construct(string $eventId, EventInterface $event, ?DateTime $occurredAt = null)
    {
        $this->eventId = $eventId;
        $this->payload = $event;
        $this->occurredAt = $occurredAt;
        $this->eventType = (new \ReflectionClass($event))->getShortName();
    }
}
