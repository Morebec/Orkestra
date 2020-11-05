<?php

namespace Tests\Morebec\Orkestra\Messaging;

use Morebec\Orkestra\Messaging\Command\DomainCommandHandlerResponse;
use Morebec\Orkestra\Messaging\DomainMessageBus;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\DomainResponseInterface;
use Morebec\Orkestra\Messaging\DomainResponseStatusCode;
use Morebec\Orkestra\Messaging\Middleware\DomainMessageBusMiddlewareInterface;
use PHPUnit\Framework\TestCase;

class DomainMessageBusTest extends TestCase
{
    public function testSendMessage()
    {
        $middlewares = [
            $this->createMiddlewareA(),
            $this->createMiddlewareB(),
            $this->createMiddlewareC(),
        ];

        $bus = new DomainMessageBus($middlewares);

        $message = $this->getMockBuilder(DomainMessageInterface ::class)->getMock();
        /** @var DomainMessageInterface $message */
        $response = $bus->sendMessage($message, new DomainMessageHeaders());

        $this->assertTrue($response->isSuccess());
    }

    public function createMiddlewareA(): DomainMessageBusMiddlewareInterface
    {
        return new class() implements DomainMessageBusMiddlewareInterface {
            public function handle(DomainMessageInterface $domainRequest, DomainMessageHeaders $headers, callable $next): DomainResponseInterface
            {
                return $next($domainRequest, $headers);
            }
        };
    }

    public function createMiddlewareB(): DomainMessageBusMiddlewareInterface
    {
        return new class() implements DomainMessageBusMiddlewareInterface {
            public function handle(DomainMessageInterface $domainRequest, DomainMessageHeaders $headers, callable $next): DomainResponseInterface
            {
                return $next($domainRequest, $headers);
            }
        };
    }

    public function createMiddlewareC(): DomainMessageBusMiddlewareInterface
    {
        return new class() implements DomainMessageBusMiddlewareInterface {
            public function handle(DomainMessageInterface $domainRequest, DomainMessageHeaders $headers, callable $next): DomainResponseInterface
            {
                return new DomainCommandHandlerResponse('test_command_handler', DomainResponseStatusCode::SUCCEEDED());
            }
        };
    }
}
