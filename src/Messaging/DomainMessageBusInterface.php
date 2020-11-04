<?php

namespace Morebec\Orkestra\Messaging;

use Morebec\Orkestra\Messaging\Middleware\DomainMessageBusMiddlewareInterface;

/**
 * A Domain message bus is responsible for sending messages to subscribed {@link DomainMessageHandlerInterface}.
 */
interface DomainMessageBusInterface
{
    /**
     * Sends a message through this bus.
     * This function should never fail and should always return a response.
     * The only cases where it is allowed to throw exceptions is for cases of misconfiguration of the bus itself,
     * to indicate that there is a problem with the configuration of the message bus.
     * Otherwise all Domain Exceptions that are thrown by handlers should be transformed to responses.
     *
     * @param DomainMessageHeaders|null $headers additional optional headers to be sent with the message
     */
    public function sendMessage(DomainMessageInterface $message, ?DomainMessageHeaders $headers = null): DomainResponseInterface;

    /**
     * Appends new middleware to this message bus.
     */
    public function appendMiddleware(DomainMessageBusMiddlewareInterface $middleware): void;

    /**
     * Prepends new middleware to this message bus.
     */
    public function prependMiddleware(DomainMessageBusMiddlewareInterface $middleware): void;
}
