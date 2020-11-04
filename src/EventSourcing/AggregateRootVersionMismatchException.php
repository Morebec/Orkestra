<?php

namespace Morebec\Orkestra\EventSourcing;

use Morebec\Orkestra\EventSourcing\EventStore\ConcurrencyException;
use Morebec\Orkestra\Modeling\AggregateRootIdentifierInterface;
use Throwable;

/**
 * Exception indicating the a loaded aggregate root's version did not match an expected version number.
 * Essentially, this means that multiple parties were trying to modify the aggregate root concurrently.
 */
class AggregateRootVersionMismatchException extends ConcurrencyException
{
    /**
     * @var AggregateRootIdentifierInterface
     */
    private $aggregateRootIdentifier;
    /**
     * @var int
     */
    private $expectedVersion;
    /**
     * @var int
     */
    private $actualVersion;

    public function __construct(
        AggregateRootIdentifierInterface $aggregateRootIdentifier,
        int $expectedVersion,
        int $actualVersion,
        Throwable $previous = null
    ) {
        parent::__construct("
            The version of aggregate root with ID {$aggregateRootIdentifier} was expected to be {$expectedVersion}, got {$actualVersion}",
            0,
            $previous
        );
        $this->aggregateRootIdentifier = $aggregateRootIdentifier;
        $this->expectedVersion = $expectedVersion;
        $this->actualVersion = $actualVersion;
    }

    public function getAggregateRootId(): AggregateRootIdentifierInterface
    {
        return $this->aggregateRootIdentifier;
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
