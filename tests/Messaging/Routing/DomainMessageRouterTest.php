<?php

namespace Tests\Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRoute;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRouter;
use PHPUnit\Framework\TestCase;

class DomainMessageRouterTest extends TestCase
{
    public function testGetRoutes(): void
    {
        $router = new DomainMessageRouter();
        $this->assertEmpty($router->getRoutes());

        $router->registerRoute(new DomainMessageRoute('message', 'handler', 'method'));
        $this->assertCount(1, $router->getRoutes());
    }

    public function testClearRoutes(): void
    {
        $router = new DomainMessageRouter();
        $router->registerRoute(new DomainMessageRoute('message', 'handler', 'method'));

        $router->clearRoutes();
        $this->assertEmpty($router->getRoutes());
    }

    public function testRegisterRoute(): void
    {
        $router = new DomainMessageRouter();

        // Adding twice the same route should not add it twice.
        $router->registerRoute(new DomainMessageRoute('message', 'handler', 'method'));
        $router->registerRoute(new DomainMessageRoute('message', 'handler', 'method'));

        $this->assertCount(1, $router->getRoutes());
    }

    public function testRegisterRoutes(): void
    {
        $router = new DomainMessageRouter();

        // Adding twice the same route should not add it twice.
        $router->registerRoutes(
            [
                new DomainMessageRoute('message', 'handler', 'method'),
                new DomainMessageRoute('message', 'handler', 'method'),
                new DomainMessageRoute('message', 'handler2', 'method'),
            ]
        );

        $this->assertCount(2, $router->getRoutes());
    }

    public function testRouteMessage(): void
    {
        $router = new DomainMessageRouter();

        // Adding twice the same route should not add it twice.
        $router->registerRoutes(
            [
                new DomainMessageRoute('message', 'handler', 'method'),
                new DomainMessageRoute('message', 'handler2', 'method'),
                new DomainMessageRoute('messageA', 'handler2', 'method'),
            ]
        );

        $message = $this->createMessage();
        $routes = $router->routeMessage($message, new DomainMessageHeaders());

        $this->assertCount(2, $routes);
        $routesArray = $routes->toArray();
        $this->assertEquals(
            new DomainMessageRoute('message', 'handler', 'method'),
            $routesArray[0]
        );
        $this->assertEquals(
            new DomainMessageRoute('message', 'handler2', 'method'),
            $routesArray[1]
        );
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
