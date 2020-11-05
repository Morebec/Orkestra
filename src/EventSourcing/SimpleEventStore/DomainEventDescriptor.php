<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\EventStore\EventDescriptorInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventMetadataInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventTypeInterface;
use Morebec\Orkestra\Messaging\Event\DomainEventInterface;

/**
 * Event Descriptor implementation tailored for domain events.
 */
class DomainEventDescriptor implements EventDescriptorInterface
{
    /**
     * @var EventId
     */
    private $eventId;

    /**
     * @var EventMetadata
     */
    private $eventMetadata;

    /**
     * @var EventType
     */
    private $eventType;

    /**
     * @var DomainEventInterface
     */
    private $event;

    private function __construct(EventIdInterface $eventId, EventTypeInterface $eventType, $event, EventMetadata $metadata)
    {
        $this->eventId = $eventId;
        $this->eventType = $eventType;
        $this->eventMetadata = $metadata;
        $this->event = $event;
    }

    public static function forDomainEvent(
        EventIdInterface $eventId,
        DomainEventInterface $event,
        ?EventMetadata $metadata = null
    ): self {
        return new static(
            $eventId,
            EventType::fromString($event::getTypeName()),
            $event,
            $metadata ?: new EventMetadata()
        );
    }

    public function getEventId(): EventIdInterface
    {
        return $this->eventId;
    }

    public function getEventType(): EventTypeInterface
    {
        return $this->eventType;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getEventMetadata(): EventMetadataInterface
    {
        return $this->eventMetadata;
    }
}
