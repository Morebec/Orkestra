<?php

namespace Morebec\Orkestra\EventSourcing;

use Morebec\Orkestra\EventSourcing\EventStore\ConcurrencyException;
use Morebec\Orkestra\Modeling\AggregateRootIdentifierInterface;
use Throwable;

/**
 * Exception indicating that a given event stream version did not match an expected version number.
 * Essentially, this means that multiple parties were trying to modify the stream concurrently.
 */
class EventStreamVersionMismatchException extends ConcurrencyException
{
    /**
     * @var AggregateRootIdentifierInterface
     */
    private $eventStreamName;
    /**
     * @var int
     */
    private $expectedVersion;
    /**
     * @var int
     */
    private $actualVersion;

    public function __construct(
        string $eventStreamName,
        int $expectedVersion,
        int $actualVersion,
        Throwable $previous = null
    ) {
        parent::__construct("
            The version of stream '{$eventStreamName}' was expected to be {$expectedVersion}, got {$actualVersion}",
            0,
            $previous
        );
        $this->eventStreamName = $eventStreamName;
        $this->expectedVersion = $expectedVersion;
        $this->actualVersion = $actualVersion;
    }

    public function getEventStreamName(): AggregateRootIdentifierInterface
    {
        return $this->eventStreamName;
    }

    public function getActualVersion(): int
    {
        return $this->actualVersion;
    }

    public function getExpectedVersion(): int
    {
        return $this->expectedVersion;
    }
}
