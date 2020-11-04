<?php

namespace Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageBusInterface;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * The Domain Message Router is used by the {@link DomainMessageBusInterface}'s middleware
 * to route a message to the right handlers.
 */
interface DomainMessageRouterInterface
{
    /**
     * Registers a route with the router.
     * If this route is already registered, aborts the registration process.
     */
    public function registerRoute(DomainMessageRouteInterface $route): void;

    /**
     * Registers multiple routes.
     * If one of route is already registered, aborts the registration process.
     *
     * @param iterable|DomainMessageRouteInterface[] $routes
     */
    public function registerRoutes(iterable $routes): void;

    /**
     * Routes a certain message, i.e. it returns the routes that match a given message.
     */
    public function routeMessage(DomainMessageInterface $message, DomainMessageHeaders $headers): DomainMessageRouteCollection;

    /**
     * Returns the list of routes registered with this router.
     *
     * @return iterable|DomainMessageRouteInterface[]
     */
    public function getRoutes(): iterable;

    /**
     * Removes all the routes registered with this router.
     */
    public function clearRoutes(): void;
}
