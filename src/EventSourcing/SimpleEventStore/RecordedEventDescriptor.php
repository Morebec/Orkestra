<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\EventStore\EventDescriptorInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventMetadataInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamVersionInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventTypeInterface;
use Morebec\Orkestra\EventSourcing\EventStore\RecordedEventDescriptorInterface;
use Morebec\Orkestra\Messaging\Event\DomainEventInterface;

/**
 * Default implementation of a RecordedEventDescriptor.
 */
class RecordedEventDescriptor implements RecordedEventDescriptorInterface
{
    /**
     * @var EventId
     */
    private $eventId;
    /**
     * @var EventType
     */
    private $eventType;
    /**
     * @var EventMetadata
     */
    private $eventMetadata;
    /**
     * @var EventStreamId
     */
    private $streamId;
    /**
     * @var EventStreamVersion
     */
    private $streamVersion;

    /** @var DomainEventInterface */
    private $event;

    private function __construct(
        EventIdInterface $eventId,
        EventTypeInterface $eventType,
        EventMetadataInterface $eventMetadata,
        DomainEventInterface $event,
        EventStreamIdInterface $streamId,
        EventStreamVersionInterface $streamVersion
    ) {
        $this->eventId = $eventId;
        $this->eventType = $eventType;
        $this->eventMetadata = $eventMetadata;
        $this->streamId = $streamId;
        $this->streamVersion = $streamVersion;
        $this->event = $event;
    }

    /**
     * Constructs a new instance from an Event Descriptor.
     *
     * @return static
     */
    public static function fromEventDescriptor(
        EventDescriptorInterface $eventDescriptor,
        EventStreamIdInterface $streamId,
        EventStreamVersionInterface $streamVersion
    ): self {
        return new self(
            $eventDescriptor->getEventId(),
            $eventDescriptor->getEventType(),
            $eventDescriptor->getEventMetadata(),
            $eventDescriptor->getEvent(),
            $streamId,
            $streamVersion
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getEventId(): EventIdInterface
    {
        return $this->eventId;
    }

    /**
     * {@inheritdoc}
     */
    public function getEventType(): EventTypeInterface
    {
        return $this->eventType;
    }

    /**
     * {@inheritdoc}
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * {@inheritdoc}
     */
    public function getEventMetadata(): EventMetadataInterface
    {
        return $this->eventMetadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getStreamId(): EventStreamIdInterface
    {
        return $this->streamId;
    }

    /**
     * {@inheritdoc}
     */
    public function getStreamVersion(): EventStreamVersionInterface
    {
        return $this->streamVersion;
    }
}
