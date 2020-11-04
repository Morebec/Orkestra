<?php

namespace Morebec\Orkestra\Messaging\Routing\Tenant;

use Morebec\Orkestra\Messaging\Routing\DomainMessageHandlerRouteBuilder;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRouteCollection;
use Morebec\Orkestra\Messaging\Routing\DomainMessageRouteInterface;
use ReflectionException;

/**
 * Builder class providing a Fluent API to define route overrides
 * for Tenant Specific Message Handlers.
 */
class TenantSpecificMessageHandlerRouteBuilder
{
    /**
     * @var string
     */
    private $tenantId;

    /**
     * @var string
     */
    private $tenantSpecificDomainMessageHandlerClassName;

    /**
     * @var array
     */
    private $overriddenDomainMessageHandlersClassNames;

    /**
     * @var string[]
     */
    private $disabledMethods;

    public function __construct(string $tenantId, string $tenantSpecificDomainMessageHandlerClassName)
    {
        $this->tenantSpecificDomainMessageHandlerClassName = $tenantSpecificDomainMessageHandlerClassName;
        $this->tenantId = $tenantId;
        $this->overriddenDomainMessageHandlersClassNames = [];
        $this->disabledMethods = [];
    }

    public static function forDomainMessageHandler(string $tenantId, string $tenantSpecificDomainMessageHandlerClassName): self
    {
        return new self($tenantId, $tenantSpecificDomainMessageHandlerClassName);
    }

    public function overridesMessageHandler(string $overriddenDomainMessageHandlerClassName): self
    {
        // In order to override a message handler, the tenant specific message handler, must extend the class.
        // Make this check here and throw exception if it is not the case.
        if (!is_a($this->tenantSpecificDomainMessageHandlerClassName, $overriddenDomainMessageHandlerClassName, true)) {
            throw new \InvalidArgumentException(sprintf('"%s" must extend "%s" to override its routes.', $this->tenantSpecificDomainMessageHandlerClassName, $overriddenDomainMessageHandlerClassName));
        }

        $this->overriddenDomainMessageHandlersClassNames[$overriddenDomainMessageHandlerClassName] = $overriddenDomainMessageHandlerClassName;

        return $this;
    }

    /**
     * @return static
     */
    public function withMethodDisabled(string $methodName): self
    {
        $this->disabledMethods[$methodName] = $methodName;

        return $this;
    }

    /**
     * Builds the routes according to the definition.
     *
     * @return DomainMessageRouteInterface[]
     *
     * @throws ReflectionException
     */
    public function build(): iterable
    {
        // Build a list of all the overridden routes.
        $overriddenRoutes = new DomainMessageRouteCollection();
        foreach ($this->overriddenDomainMessageHandlersClassNames as $overriddenDomainMessageHandlersClassName) {
            $baseBuilder = DomainMessageHandlerRouteBuilder::forDomainMessageHandler($overriddenDomainMessageHandlersClassName);
            foreach ($this->disabledMethods as $disabledMethod) {
                $baseBuilder->withMethodDisabled($disabledMethod);
            }
            $defaultRoutes = $baseBuilder->build();
            /** @var DomainMessageRouteInterface $defaultRoute */
            foreach ($defaultRoutes as $defaultRoute) {
                $overriddenRoutes->add(
                    new TenantSpecificRoute(
                        $this->tenantId,
                        $defaultRoute->getDomainMessageTypeName(),
                        $this->tenantSpecificDomainMessageHandlerClassName,
                        $defaultRoute->getDomainMessageHandlerMethodName(),
                        $defaultRoute
                    )
                );
            }
        }

        // Build a list of Routes that are on the Tenant. (This will include the extended methods).
        // The tenant routes contain all the routes of a handler as if it were a normal handler.
        // We'll need to remove from this collection, the methods that are overridden.
        $tenantBuilder = DomainMessageHandlerRouteBuilder::forDomainMessageHandler($this->tenantSpecificDomainMessageHandlerClassName);
        foreach ($this->disabledMethods as $disabledMethod) {
            $tenantBuilder->withMethodDisabled($disabledMethod);
        }
        $tenantBaseRoutes = $tenantBuilder
            ->build()
            ->filter(static function (DomainMessageRouteInterface $route) use ($overriddenRoutes) {
                foreach ($overriddenRoutes as $overriddenRoute) {
                    $sameMethod = $overriddenRoute->getDomainMessageHandlerMethodName() === $route->getDomainMessageHandlerMethodName();
                    if ($sameMethod) {
                        return false;
                    }
                }

                return true;
            }
        );
        // Make all tenant routes actual instances of tenant routes
        $tenantRoutes = new DomainMessageRouteCollection();
        foreach ($tenantBaseRoutes as $baseRoute) {
            $tenantRoutes->add(
                new TenantSpecificRoute(
                    $this->tenantId,
                    $baseRoute->getDomainMessageTypeName(),
                    $baseRoute->getDomainMessageHandlerClassName(),
                    $baseRoute->getDomainMessageHandlerMethodName()
                )
            );
        }

        // Add the overrides.
        $tenantRoutes->addAll($overriddenRoutes);

        return $tenantRoutes;
    }
}
