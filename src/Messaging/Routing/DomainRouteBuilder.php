<?php

namespace Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\Routing\Tenant\TenantSpecificMessageHandlerRouteBuilder;

/**
 * Inspects a {@link DomainMessageHandlerInterface} through Reflection and extracts
 * the {@link DomainMessageRouteInterface} it can support.
 */
class DomainRouteBuilder
{
    public static function forMessageHandler(string $messageHandlerClassName): DomainMessageHandlerRouteBuilder
    {
        return DomainMessageHandlerRouteBuilder::forDomainMessageHandler($messageHandlerClassName);
    }

    public static function forTenantSpecificMessageHandler(string $tenantId, string $messageHandlerClassName): TenantSpecificMessageHandlerRouteBuilder
    {
        return TenantSpecificMessageHandlerRouteBuilder::forDomainMessageHandler($tenantId, $messageHandlerClassName);
    }
}
