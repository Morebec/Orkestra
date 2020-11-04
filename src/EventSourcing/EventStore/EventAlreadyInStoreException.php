<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

use Throwable;

/**
 * Thrown when an event with a given id was already in the store, but was not expected to.
 */
class EventAlreadyInStoreException extends \RuntimeException
{
    public function __construct(string $eventId, Throwable $previous = null)
    {
        parent::__construct("Event with id $eventId is already in the store", 0, $previous);
    }
}
