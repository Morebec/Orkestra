<?php

namespace Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Throwable;

/**
 * Thrown when a given message type was expected to be handled by at least one message handler.
 */
class UnhandledMessageException extends \LogicException
{
    /**
     * UnhandledMessageException constructor.
     */
    public function __construct(DomainMessageInterface $message, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Message of type "%s" was not handled.', $message::getTypeName()),
            0,
            $previous
        );
    }
}
