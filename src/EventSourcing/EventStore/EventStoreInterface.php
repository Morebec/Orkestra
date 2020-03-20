<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

use InvalidArgumentException;
use Morebec\Orkestra\EventSourcing\EventStreamVersionMismatchException;

/**
 * Interface for event stores.
 * Event stores work with the concept of streams.
 * A stream of event is a logical grouping of related events.
 * These can be per aggregate (for Aggregate based Event sourcing) or a specific service.
 */
interface EventStoreInterface
{
    /**
     * Appends a list of events to a stream. If a stream does not exists, it is created implicitly.
     * Appending to events is an idempotent action based upon the event id assigned to an event.
     * This means that if a specific event with a given is appended twice, the second time would throw
     * a EventAlreadyInStoreException.
     *
     * @param string                 $streamName      name of the stream to which to append
     * @param int                    $expectedVersion the version at which the stream is expected to be, to ensure
     *                                                an optimistic concurrency check
     * @param iterable<EventDescriptor> $events          list of events to append
     *
     * @throws EventAlreadyInStoreException
     * @throws InvalidArgumentException     if has an invalid name such as an empty string or if the implementation requires strict naming rules
     * @throws EventStreamVersionMismatchException
     */
    public function appendToStream(string $streamName, int $expectedVersion, iterable $events): void;

    /**
     * Reads the stream at a specific version and returns the associated event descriptor or null if it was not found
     *
     * @throws InvalidArgumentException if the stream does not exist or has an invalid name
     */
    public function readStreamAtVersion(string $streamName, int $version): ?EventDescriptor;

    /**
     * Reads a specific stream forwards from a starting point.
     *
     * @param string $streamName   name of the stream to read
     * @param int    $startVersion start version
     * @param bool   $includeStart indicates if the starting point should be included in the returned list of events
     *
     * @return iterable<EventDescriptor>
     *
     * @throws InvalidArgumentException if the stream does not exist or has an invalid name
     */
    public function readStreamAtVersionForward(string $streamName, int $startVersion, bool $includeStart = true): iterable;

    /**
     * Reads all the events of a stream from the start.
     *
     * @return iterable<EventDescriptor>
     *
     * @throws InvalidArgumentException if the stream does not exist or has an invalid name
     */
    public function readStreamFromStartForward(string $streamName): iterable;

    /**
     * Reads all the events of all the streams.
     *
     * @return iterable<EventDescriptor>
     */
    public function readAllFromTimestampForward(float $timestamp): iterable;

    /**
     * Reads all the events of all the stream from a specific event with id and forward.
     *
     * @return iterable<EventDescriptor>
     */
    public function readAllFromEventIdForward(string $eventId, bool $includeStart = true): iterable;

    /**
     * Returns the latest event.
     */
    public function readLatest(): ?EventDescriptor;

    /**
     * Returns the version of a stream.
     *
     * @throws InvalidArgumentException if the stream does not exist or has an invalid name
     */
    public function findEventStreamVersion(string $streamName): int;
}
