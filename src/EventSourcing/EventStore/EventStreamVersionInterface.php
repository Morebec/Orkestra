<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

interface EventStreamVersionInterface
{
    /**
     * Constructs an instance of this class using an int.
     *
     * @return static
     */
    public static function fromInt(int $version): self;

    /**
     * Returns the initial version of a stream.
     *
     * @return static
     */
    public static function initial(): self;

    /**
     * Indicates if this stream version is equal to another stream version.
     */
    public function isEqualTo(self $version): bool;

    /**
     * Returns the version as an int.
     */
    public function toInt(): int;
}
