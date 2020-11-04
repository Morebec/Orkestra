<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

/**
 * An Event Descriptor is a informational wrapper around an event
 * so that the event store can effectively work with domain events.
 * Event descriptors are essentially what are going to be saved in the database.
 * Event Store never works directly with events coming from the outside world,
 * it only understands EventDescriptors.
 */
interface EventDescriptorInterface
{
    /**
     * Returns the ID of the event.
     */
    public function getEventId(): EventIdInterface;

    /**
     * Returns the Type of the event.
     */
    public function getEventType(): EventTypeInterface;

    /**
     * Returns the event described by this descriptor instance.
     *
     * @return mixed
     */
    public function getEvent();

    /**
     * Represents additional metadata about an event.
     */
    public function getEventMetadata(): EventMetadataInterface;
}
