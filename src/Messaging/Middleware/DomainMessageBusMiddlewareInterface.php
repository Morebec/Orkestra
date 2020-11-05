<?php

namespace Morebec\Orkestra\Messaging\Middleware;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\DomainResponseInterface;

/**
 * Represents a middleware to allow injecting logic inside the Domain Message Bus.
 */
interface DomainMessageBusMiddlewareInterface
{
    /**
     * Handles a given {@link DomainMessageInterface} according to this middlewares logic and calls next.
     *
     * @param callable $next callable with a signature taking a {@link DomainMessageInterface} and {@link DomainMessageHeaders} as its arguments
     */
    public function handle(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers, callable $next): DomainResponseInterface;
}
