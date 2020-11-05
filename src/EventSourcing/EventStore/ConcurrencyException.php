<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

use Throwable;

/**
 * Thrown when there is a concurrency issue while trying to append events to a stream.
 */
class ConcurrencyException extends \RuntimeException implements EventStoreExceptionInterface
{
    /**
     * @var EventStreamIdInterface
     */
    private $streamId;

    /**
     * @var EventStreamVersionInterface
     */
    private $expectedStreamVersion;

    /**
     * @var EventStreamVersionInterface
     */
    private $actualStreamVersion;

    public function __construct(
        EventStreamIdInterface $streamId,
        EventStreamVersionInterface $expectedStreamVersion,
        EventStreamVersionInterface $actualStreamVersion,
        $code = 0,
        Throwable $previous = null
    ) {
        $message = sprintf(
            'Event Store: Concurrency issue encountered on stream with ID %s, expected version: %s, actual version: %s.',
            $streamId,
            $expectedStreamVersion,
            $actualStreamVersion
        );
        parent::__construct($message, $code, $previous);
        $this->streamId = $streamId;
        $this->expectedStreamVersion = $expectedStreamVersion;
        $this->actualStreamVersion = $actualStreamVersion;
    }

    public function getStreamId(): EventStreamIdInterface
    {
        return $this->streamId;
    }

    public function getExpectedStreamVersion(): EventStreamVersionInterface
    {
        return $this->expectedStreamVersion;
    }

    public function getActualStreamVersion(): EventStreamVersionInterface
    {
        return $this->actualStreamVersion;
    }
}
