<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

use Throwable;

/**
 * Thrown when an exception was expected to be found but was not.
 */
class ProjectionNotFoundException extends ProjectionException
{
    public function __construct($message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    /**
     * Constructs an instance of this exception for a projection of a given class with a certain ID.
     *
     * @return static
     */
    public static function for(string $id, string $class, ?\Throwable $previous = null): self
    {
        return new self(sprintf('Projection "%s" of type "%s" was not found.', $id, $class), $previous);
    }
}
