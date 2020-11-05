<?php

namespace Morebec\Orkestra\Messaging\Context;

use Throwable;

/**
 * Exception thrown when there is no context to be ended.
 */
class NoDomainMessageBusContextToEndException extends \RuntimeException
{
    public function __construct(Throwable $previous = null)
    {
        parent::__construct('There is no context to be ended in the Domain Message Bus.', 0, $previous);
    }
}
