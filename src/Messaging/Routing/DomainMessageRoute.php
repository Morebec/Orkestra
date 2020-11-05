<?php

namespace Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageHandlerInterface;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * Represents a route for a given {@link DomainMessageInterface} to a {@link DomainMessageHandlerInterface}.
 */
class DomainMessageRoute implements DomainMessageRouteInterface
{
    /**
     * @var string
     */
    private $domainMessageTypeName;

    /**
     * @var string
     */
    private $messageHandlerClassName;

    /**
     * @var string
     */
    private $messageHandlerMethodName;

    public function __construct(
        string $domainMessageTypeName,
        string $messageHandlerClassName,
        string $messageHandlerMethodName
    ) {
        $this->domainMessageTypeName = $domainMessageTypeName;
        $this->messageHandlerClassName = $messageHandlerClassName;
        $this->messageHandlerMethodName = $messageHandlerMethodName;
    }

    public function __toString(): string
    {
        return $this->getId();
    }

    /**
     * Indicates if this route matches a certain domain message.
     */
    public function matches(DomainMessageInterface $message, DomainMessageHeaders $headers): bool
    {
        return $this->domainMessageTypeName === $message::getTypeName();
    }

    public function getId(): string
    {
        return "{$this->domainMessageTypeName} => {$this->messageHandlerClassName}::{$this->messageHandlerMethodName}";
    }

    public function getDomainMessageTypeName(): string
    {
        return $this->domainMessageTypeName;
    }

    public function getDomainMessageHandlerMethodName(): string
    {
        return $this->messageHandlerMethodName;
    }

    public function getDomainMessageHandlerClassName(): string
    {
        return $this->messageHandlerClassName;
    }
}
