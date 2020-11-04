<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

/**
 * Implementation of an Event Descriptor that was appended to a stream.
 */
interface RecordedEventDescriptorInterface extends EventDescriptorInterface
{
    /**
     * Returns the name of the stream into which this descriptor's event was appended.
     */
    public function getStreamId(): EventStreamIdInterface;

    /**
     * Returns the version that was used for this event descriptor.
     */
    public function getStreamVersion(): EventStreamVersionInterface;
}
