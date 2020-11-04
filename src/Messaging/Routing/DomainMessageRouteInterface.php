<?php

namespace Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * Represents a route for a given {@link DomainMessageInterface} to a {@link DomainMessageHandlerInterface}.
 */
interface DomainMessageRouteInterface
{
    /**
     * Indicates.
     */
    public function matches(DomainMessageInterface $domainMessage, DomainMessageHeaders $domainMessageHeaders): bool;

    /**
     * Returns the unique ID of the route given its message type and handler.
     */
    public function getId(): string;

    /**
     * Returns the Domain Message Type Name.
     */
    public function getDomainMessageTypeName(): string;

    /**
     * Returns the name of the Message Handler Method Name.
     */
    public function getDomainMessageHandlerMethodName(): string;

    /**
     * Returns the Class Name of the Domain Message Handler.
     */
    public function getDomainMessageHandlerClassName(): string;
}
