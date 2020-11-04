<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

/**
 * Exception thrown when there appears to have concurrency issues with an aggregate's persistence.
 * These exceptions are triggered when there is a version mismatch.
 */
class ConcurrencyException extends \RuntimeException
{
}
