<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

use Morebec\Orkestra\EventSourcing\EventStore\RecordedEventDescriptorInterface;
use Morebec\Orkestra\Messaging\Event\DomainEventInterface;

/**
 * Represents a Context for the projecting of an event.
 * This can contain anything required by projectors to run.
 */
interface ProjectionContextInterface
{
    /**
     * Returns the Event to be projected.
     */
    public function getEvent(): DomainEventInterface;

    /**
     * Returns an {@link RecordedEventDescriptorInterface} as Provided by the event store.
     */
    public function getEventDescriptor(): RecordedEventDescriptorInterface;

    /**
     * Contains extra information about the current projection context.
     * This can be used to add application specific data that is outside what is contained in the
     * EventDEscriptorInterface.
     */
    public function getMetadata(): ProjectionContextMetadataInterface;
}
