<?php

namespace Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageBusInterface;
use Morebec\Orkestra\Messaging\DomainMessageHandlerInterface;

/**
 * Allows the Routing components of the {@link DomainMessageBusInterface} to be able to
 * to get an instance of a {@link DomainMessageHandlerInterface}.
 */
interface DomainMessageHandlerProviderInterface
{
    /**
     * Returns a Domain Message Handler from a Class Name or returns null if not found.
     */
    public function getDomainMessageHandler(string $domainMessageHandlerClassName): ?DomainMessageHandlerInterface;
}
