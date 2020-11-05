<?php

namespace Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Throwable;

/**
 * Thrown when a message type required exactly one handler but more were found (Commands and Queries).
 */
class AmbiguousMessageHandlerException extends \LogicException
{
    public function __construct(DomainMessageInterface $message, Throwable $previous = null)
    {
        parent::__construct(
            "Multiple Message Handlers found for message {$message::getTypeName()}, expected 1.",
            0,
            $previous
        );
    }
}
