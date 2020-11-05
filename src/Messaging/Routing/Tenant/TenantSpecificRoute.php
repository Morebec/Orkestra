<?php

namespace Morebec\Orkestra\Messaging\Routing\Tenant;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRoute;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRouteInterface;

/**
 * Represents Routes that are Tenant Specific.
 * Allowing to override or extend routes for specific tenants.
 */
class TenantSpecificRoute extends DomainMessageRoute
{
    /**
     * @var string
     */
    private $tenantId;

    /**
     * Default Route that this Tenant Specific Route overrides.
     *
     * @var DomainMessageRouteInterface|null
     */
    private $overridesRoute;

    /**
     * TenantSpecificRoute constructor.
     *
     * @param string                           $tenantId                       ID of the tenant to which this route applies
     * @param string                           $domainMessageTypeNameClassName domain Message Type Name for which this Route applies
     * @param string                           $messageHandlerClassName        domain Message Handler class name
     * @param string                           $messageHandlerMethodName       domain Message Handler method name
     * @param DomainMessageRouteInterface|null $overridesRoute                 (Optional) Indicates if this route overrides another given route. If you want to simply extend set this to null.
     */
    public function __construct(
        string $tenantId,
        string $domainMessageTypeNameClassName,
        string $messageHandlerClassName,
        string $messageHandlerMethodName,
        ?DomainMessageRouteInterface $overridesRoute = null
    ) {
        parent::__construct($domainMessageTypeNameClassName, $messageHandlerClassName, $messageHandlerMethodName);
        $this->tenantId = $tenantId;
        $this->overridesRoute = $overridesRoute;
    }

    public function matches(DomainMessageInterface $domainMessage, DomainMessageHeaders $domainMessageHeaders): bool
    {
        // A: We are dealing with a single handler type of message (Command || Query).
        //    In that case if we have a tenant specific handler that matches this message
        //    We can override the configured route (if any).
        // B: We are dealing with a multi-handler message type. In that case we have to decide:
        //    - We need to override a potential route returned by the parent,
        //    - Or simply extend the functionality with an additional handler.
        $tenantMatches = $domainMessageHeaders->get(DomainMessageHeaders::TENANT_ID) === $this->tenantId;
        if (!$tenantMatches) {
            return false;
        }

        if ($this->matchesOverride($domainMessage, $domainMessageHeaders)) {
            return true;
        }

        return parent::matches($domainMessage, $domainMessageHeaders);
    }

    /**
     * Indicates if this Route overrides another one.
     */
    public function overridesRoute(DomainMessageRouteInterface $route): bool
    {
        return $this->overridesRoute && $this->overridesRoute->getId() === $route->getId();
    }

    /**
     * Indicates if a given message and headers matches the override of this route.
     * If there is no override will return false.
     */
    public function matchesOverride(DomainMessageInterface $message, DomainMessageHeaders $headers): bool
    {
        if (!$this->overridesRoute) {
            return false;
        }

        return $this->overridesRoute->matches($message, $headers);
    }

    public function getId(): string
    {
        $parentId = parent::getId();
        $routeId = "{$parentId}@{$this->tenantId}";

        if ($this->overridesRoute) {
            $routeId = "$routeId <- {$this->overridesRoute->getId()}";
        }

        return $routeId;
    }
}
