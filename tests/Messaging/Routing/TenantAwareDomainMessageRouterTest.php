<?php

namespace Tests\Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageHandlerInterface;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRoute;
use Morebec\Orkestra\Messaging\Routing\Tenant\TenantAwareDomainMessageRouter;
use Morebec\Orkestra\Messaging\Routing\Tenant\TenantSpecificRoute;
use PHPUnit\Framework\TestCase;

class TenantAwareDomainMessageRouterTest extends TestCase
{
    public function testRouteMessage(): void
    {
        $router = new TenantAwareDomainMessageRouter();

        $defaultHandler = $this->createDefaultHandler();
        $tenantSpecificHandler = $this->createTenantSpecificHandler();
        $message = $this->createDomainMessage();

        $defaultRoute = new DomainMessageRoute(
            $message::getTypeName(),
            \get_class($defaultHandler),
            'onMessage'
        );

        $anotherDefaultRoute = new DomainMessageRoute(
            $message::getTypeName(),
            \get_class($defaultHandler),
            'onMessageOther'
        );

        $tenantId = 'tenantId';
        $tenantSpecificRoute = new TenantSpecificRoute(
            $tenantId,
            $message::getTypeName(),
            \get_class($tenantSpecificHandler),
            'onMessage',
            $defaultRoute
        );

        $router->registerRoutes([
            $defaultRoute,
            $anotherDefaultRoute,
            $tenantSpecificRoute,
        ]);

        // The tenant Specific Route overrides the default route, but not the anotherDefaultRoute,
        // So we should have TenantSpecific and anotherDefaultRoute
        $routes = $router->routeMessage($message, new DomainMessageHeaders([
            DomainMessageHeaders::TENANT_ID => $tenantId,
        ]));

        $this->assertCount(2, $routes); // The default route should not be there.
    }

    private function createDomainMessage(): DomainMessageInterface
    {
        return new class() implements DomainMessageInterface {
            public static function getTypeName(): string
            {
                return 'domain_message';
            }
        };
    }

    private function createDefaultHandler(): DomainMessageHandlerInterface
    {
        return new class() implements DomainMessageHandlerInterface {
        };
    }

    private function createTenantSpecificHandler(): DomainMessageHandlerInterface
    {
        return new class() implements DomainMessageHandlerInterface {
        };
    }
}
