<?php

namespace Morebec\Orkestra\Messaging\Context;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * The Domain Context Manager is responsible for managing the current message bus context.
 * It should be used solely by the responsible middleware of the {@link DomainMessageBusInterface},
 * namely the {@link BuildDomainContextMiddleware}.
 */
interface DomainContextManagerInterface
{
    /**
     * Starts a new context for a given message with headers.
     */
    public function startContext(DomainMessageInterface $message, DomainMessageHeaders $headers): void;

    /**
     * Ends the currently active context or throw an exception if there was no started context.
     *
     * @throws NoDomainMessageBusContextToEndException
     */
    public function endContext(): void;

    /**
     * Returns the current context or null if there is none.
     */
    public function getContext(): ?DomainContext;
}
