<?php

namespace Tests\Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageHandlerInterface;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\Routing\Tenant\TenantSpecificMessageHandlerRouteBuilder;
use PHPUnit\Framework\TestCase;

class TenantSpecificMessageHandlerRouteBuilderTest extends TestCase
{
    public function testBuild(): void
    {
        // DEF
        //    methodA =: message => DEF => methodA
        //    methodB =: message => DEF => methodB
        //
        // TS extends DEF
        //    methodA =: message => TS => methodA <- message => DEF => methodA
        //    methodC =: message => TS => methodC
        //   // Since B is not specified it should be routed the normal handler
        //
        // Result
        //    message => DEF => methodA (not built, should be defined elsewhere)
        //    message => DEF => methodB (not built, should be defined elsewhere)
        //    message => TS => methodA <- message => DEF => methodA (Builder)
        //    message => TS => methodB <- message => DEF => methodB (Builder)
        //    message => TS => methodC (Builder)

        $routes = TenantSpecificMessageHandlerRouteBuilder::forDomainMessageHandler(
            'tenantId',
            TenantSpecificMessageHandler::class
        )
            ->overridesMessageHandler(DefaultMessageHandler::class)
            ->build();

        $this->assertCount(3, $routes);

        $this->expectException(\InvalidArgumentException::class);
        TenantSpecificMessageHandlerRouteBuilder::forDomainMessageHandler(
            'tenantId',
            TenantSpecificMessageHandler::class
        )
            ->overridesMessageHandler(UnrelatedMessageHandler::class)
            ->build();
    }
}

class Message implements DomainMessageInterface
{
    public static function getTypeName(): string
    {
        return 'message';
    }
}

class UnrelatedMessageHandler implements DomainMessageHandlerInterface
{
    public function handleMessageX(Message $message): void
    {
    }

    public function handleMessageY(Message $message): void
    {
    }
}

class DefaultMessageHandler implements DomainMessageHandlerInterface
{
    public function handleMessageA(Message $message): void
    {
    }

    public function handleMessageB(Message $message): void
    {
    }
}

class TenantSpecificMessageHandler extends DefaultMessageHandler
{
    public function handleMessageA(Message $message): void
    {
    }

    public function handleMessageC(Message $message): void
    {
    }
}
