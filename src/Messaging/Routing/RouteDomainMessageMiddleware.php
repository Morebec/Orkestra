<?php

namespace Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageHandlerInterface;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\DomainResponseInterface;
use Morebec\Orkestra\Messaging\Middleware\DomainMessageBusMiddlewareInterface;

/**
 * Middleware responsible for finding the routes to {@link DomainMessageHandlerInterface} that match a specific {@link DomainMessageInterface}.
 * Once resolved these routes are added to the {@link DomainMessageHeaders}.
 * The reason for having this decoupled from the {@link HandleDomainMessageMiddleware} is to allow other middleware
 * to manipulate these routes in the headers prior to the messages getting sent ot their resolved handlers.
 */
class RouteDomainMessageMiddleware implements DomainMessageBusMiddlewareInterface
{
    /**
     * @var DomainMessageRouterInterface
     */
    private $router;

    public function __construct(DomainMessageRouterInterface $router)
    {
        $this->router = $router;
    }

    public function handle(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers, callable $next): DomainResponseInterface
    {
        // Only resolve routes using the router if it is not already set in the headers.

        $destinationHandlers = $headers->get(DomainMessageHeaders::DESTINATION_HANDLER_NAMES);

        if (!$destinationHandlers) {
            $routes = $this->router->routeMessage($domainMessage, $headers);
            $routesAsString = array_map(static function (DomainMessageRouteInterface $r) {
                return "{$r->getDomainMessageHandlerClassName()}::{$r->getDomainMessageHandlerMethodName()}";
            }, $routes->toArray());

            $headers->set(DomainMessageHeaders::DESTINATION_HANDLER_NAMES, $routesAsString);
        }

        return $next($domainMessage, $headers, $next);
    }
}
