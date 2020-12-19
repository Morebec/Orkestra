<?php

namespace Tests\Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageHandlerResponse;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\DomainResponseStatusCode;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRoute;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRouter;
use Morebec\Orkestra\Messaging\Routing\RouteDomainMessageMiddleware;
use PHPUnit\Framework\TestCase;

class RouteDomainMessageMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $router = new DomainMessageRouter();
        $middleware = new RouteDomainMessageMiddleware($router);

        $message = $this->createMessage();

        $router->registerRoute(new DomainMessageRoute(
            $message::getTypeName(),
            'handler',
            'method'
        ));

        $headers = new DomainMessageHeaders();
        $nextMiddleware = static function (DomainMessageInterface $domainMessage, DomainMessageHeaders $headers) {
            return new DomainMessageHandlerResponse('handlerName', DomainResponseStatusCode::SUCCEEDED());
        };

        $middleware->handle($message, $headers, $nextMiddleware);

        $this->assertNotEmpty($headers->get(DomainMessageHeaders::DESTINATION_HANDLER_NAMES));
        $this->assertEquals(['handler::method'], $headers->get(DomainMessageHeaders::DESTINATION_HANDLER_NAMES));

        // Test Handler not resolved if destination handler already set.
        $headers = new DomainMessageHeaders([
            DomainMessageHeaders::DESTINATION_HANDLER_NAMES => ['specificHandler::specificMethod'],
        ]);
        $middleware->handle($message, $headers, $nextMiddleware);

        $this->assertEquals(['specificHandler::specificMethod'], $headers->get(DomainMessageHeaders::DESTINATION_HANDLER_NAMES));
    }

    private function createMessage(): DomainMessageInterface
    {
        return new class() implements DomainMessageInterface {
            public static function getTypeName(): string
            {
                return 'message';
            }
        };
    }
}
