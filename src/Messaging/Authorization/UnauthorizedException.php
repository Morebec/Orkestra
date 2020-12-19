<?php

namespace Morebec\Orkestra\Messaging\Authorization;

use Morebec\Orkestra\Modeling\DomainExceptionInterface;
use Throwable;

/**
 * Thrown when a given message was intended to be handled, but the current actor or process
 * did not have the required privileges.
 */
class UnauthorizedException extends \RuntimeException implements DomainExceptionInterface
{
    public function __construct(string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
