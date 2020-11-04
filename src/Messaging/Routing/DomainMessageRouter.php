<?php

namespace Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * Default Implementation of a {@link DomainMessageRouterInterface}.
 */
class DomainMessageRouter implements DomainMessageRouterInterface
{
    /**
     * @var DomainMessageRouteCollection
     */
    private $routes;

    public function __construct()
    {
        $this->routes = new DomainMessageRouteCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function registerRoute(DomainMessageRouteInterface $route): void
    {
        $this->routes->add($route);
    }

    /**
     * {@inheritdoc}
     */
    public function registerRoutes(iterable $routes): void
    {
        foreach ($routes as $route) {
            $this->registerRoute($route);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function routeMessage(DomainMessageInterface $message, DomainMessageHeaders $headers): DomainMessageRouteCollection
    {
        return $this->routes->filter(
            static function (DomainMessageRoute $route) use ($headers, $message) {
                return $route->matches($message, $headers);
            }
        );
    }

    public function getRoutes(): iterable
    {
        return $this->routes;
    }

    public function clearRoutes(): void
    {
        $this->routes->removeAll();
    }
}
