<?php

namespace Morebec\Orkestra\Messaging\Routing\Tenant;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRoute;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRouteCollection;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRouteInterface;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRouter;

/**
 * Decorator Implementation of a Domain Message Router.
 */
class TenantAwareDomainMessageRouter extends DomainMessageRouter
{
    /**
     * @var DomainMessageRouteCollection
     */
    private $tenantRoutes;

    public function __construct()
    {
        $this->tenantRoutes = new DomainMessageRouteCollection();
        parent::__construct();
    }

    public function registerRoute(DomainMessageRouteInterface $route): void
    {
        if ($route instanceof TenantSpecificRoute) {
            $this->tenantRoutes->add($route);

            return;
        }

        parent::registerRoute($route);
    }

    public function routeMessage(DomainMessageInterface $message, DomainMessageHeaders $headers): DomainMessageRouteCollection
    {
        // We have two cases here
        // A: We are dealing with a single handler type of message (Command || Query).
        //    In that case if we have a tenant specific handler that matches this message
        //    We can override the configured route (if any).
        // B: We are dealing with a multi-handler message type. In that case we have to decide:
        //    - We need to override a potential route returned by the parent,
        //    - Or simply extend the functionality with an additional handler.

        $tenantRoutes = $this->tenantRoutes->filter(
            static function (DomainMessageRoute $route) use ($headers, $message) {
                return $route->matches($message, $headers);
            }
        );

        $parentRoutes = parent::routeMessage($message, $headers)->filter(
            static function (DomainMessageRouteInterface $parentRoute) use ($tenantRoutes) {
                /** @var TenantSpecificRoute $tenantRoute */
                foreach ($tenantRoutes as $tenantRoute) {
                    if ($tenantRoute->overridesRoute($parentRoute)) {
                        return false;
                    }
                }

                return true;
            }
        );

        $allRoutes = new DomainMessageRouteCollection($tenantRoutes);
        $allRoutes->addAll($parentRoutes);

        return $allRoutes;
    }

    public function getRoutes(): iterable
    {
        $allRoutes = new DomainMessageRouteCollection($this->tenantRoutes);
        $allRoutes->addAll(parent::getRoutes());

        return $allRoutes;
    }
}
