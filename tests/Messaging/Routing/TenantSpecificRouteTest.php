<?php

namespace Tests\Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageHandlerInterface;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRoute;
use Morebec\Orkestra\Messaging\Routing\Tenant\TenantSpecificRoute;
use PHPUnit\Framework\TestCase;

class TenantSpecificRouteTest extends TestCase
{
    public function testMatchesOverride(): void
    {
        $message = $this->createDomainMessage();

        $defaultHandler = $this->createDefaultHandler();
        $tenantSpecificHandler = $this->createTenantSpecificHandler();

        $defaultRoute = new DomainMessageRoute(
            $message::getTypeName(),
            \get_class($defaultHandler),
            'onMessage'
        );

        $tenantSpecificRoute = new TenantSpecificRoute(
            'tenantId',
            $message::getTypeName(),
            \get_class($tenantSpecificHandler),
            'onMessage',
            $defaultRoute
        );

        $this->assertTrue($tenantSpecificRoute->matchesOverride($message, new DomainMessageHeaders()));
    }

    public function testMatches(): void
    {
        $message = $this->createDomainMessage();

        $defaultHandler = $this->createDefaultHandler();
        $tenantSpecificHandler = $this->createTenantSpecificHandler();

        $defaultRoute = new DomainMessageRoute(
            $message::getTypeName(),
            \get_class($defaultHandler),
            'onMessage'
        );

        $tenantSpecificRoute = new TenantSpecificRoute(
            'tenantId',
            $message::getTypeName(),
            \get_class($tenantSpecificHandler),
            'onMessage',
            $defaultRoute
        );

        $this->assertTrue($tenantSpecificRoute->matches($message, new DomainMessageHeaders([
            DomainMessageHeaders::TENANT_ID => 'tenantId',
        ])));

        $this->assertFalse($tenantSpecificRoute->matches($message, new DomainMessageHeaders([
            DomainMessageHeaders::TENANT_ID => 'wrong_tenantId',
        ])));
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
