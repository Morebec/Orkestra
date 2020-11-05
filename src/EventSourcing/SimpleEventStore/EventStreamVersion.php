<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\EventStore\EventStreamVersionInterface;

/**
 * Returns the Version of a Stream.
 * The version of a stream indicates the number of events that were appended to it overtime.
 * This is used to check concurrency issues.
 */
class EventStreamVersion implements EventStreamVersionInterface
{
    /**
     * This initial version is actually an indication of an empty stream, as the first event appended to a stream will receive
     * the version 0.
     *
     * @var int
     */
    public const INITIAL_VERSION = -1;

    /**
     * @var int
     */
    private $value;

    /**
     * EventStreamVersion constructor.
     */
    private function __construct(int $version)
    {
        $this->value = $version;
    }

    /**
     * Constructs an instance of this class using an int.
     *
     * @return static
     */
    public static function fromInt(int $version): EventStreamVersionInterface
    {
        return new self($version);
    }

    /**
     * Returns the initial version of a stream.
     *
     * @return static
     */
    public static function initial(): EventStreamVersionInterface
    {
        return new self(self::INITIAL_VERSION);
    }

    /**
     * Returns the version as an int.
     */
    public function toInt(): int
    {
        return $this->value;
    }

    /**
     * Indicates if this stream version is equal to another stream version.
     */
    public function isEqualTo(EventStreamVersionInterface $version): bool
    {
        if ($version instanceof self) {
            return $this->value === $version->value;
        }

        return $this->toInt() === $version->toInt();
    }
}
