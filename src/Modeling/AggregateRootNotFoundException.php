<?php

namespace Morebec\Orkestra\Modeling;

use RuntimeException;
use Throwable;

/**
 * Thrown when an Aggregate Root was expected to be found.
 */
class AggregateRootNotFoundException extends RuntimeException
{
    public function __construct(AggregateRootIdentifierInterface $identifier, Throwable $previous = null)
    {
        parent::__construct("Aggregate with ID {$identifier} was not found", 0, $previous);
    }
}
