<?php

namespace Morebec\Orkestra\Messaging;

use Throwable;

/**
 * Exception thrown by the Domain Message Bus when no middleware returned a response for a given message.
 * This indicates misconfiguration in the middleware pipeline.
 */
class NoResponseFromMiddlewareException extends \RuntimeException
{
    public function __construct(DomainMessageInterface $domainMessage, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('No Middleware returned a response for message of type: "%s".', $domainMessage::getTypeName()
            ), 0, $previous);
    }
}
