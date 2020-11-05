<?php

namespace Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageHandlerInterface;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use ReflectionClass;
use ReflectionException;

/**
 * Inspects a {@link DomainMessageHandlerInterface} through Reflection and extracts
 * the {@link DomainMessageRouteInterface} it can support.
 */
class DomainMessageHandlerRouteBuilder
{
    /**
     * @var string
     */
    private $domainMessageHandlerClassName;

    /**
     * @var array
     */
    private $disabledMethods;

    public function __construct(string $domainMessageHandlerClassName)
    {
        $this->domainMessageHandlerClassName = $domainMessageHandlerClassName;
        $this->disabledMethods = [];
    }

    public static function forDomainMessageHandler(string $domainMessageHandlerClassName): self
    {
        return new static($domainMessageHandlerClassName);
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
     * @throws ReflectionException
     */
    public function build(): DomainMessageRouteCollection
    {
        $routes = [];
        $reflectionClass = new ReflectionClass($this->domainMessageHandlerClassName);
        $methods = ($reflectionClass)->getMethods();
        foreach ($methods as $method) {
            if (\in_array($method->getName(), $this->disabledMethods)) {
                continue;
            }

            if (!$method->isPublic()) {
                continue;
            }

            $params = $method->getParameters();
            if (\count($params) !== 1) {
                continue;
            }

            $eventClass = $params[0];
            $eventClassName = $eventClass->getClass()->getName();
            if (!is_subclass_of($eventClassName, DomainMessageInterface::class, true)) {
                continue;
            }

            $routes[] = new DomainMessageRoute($eventClassName::getTypeName(), $reflectionClass->getName(), $method->getName());
        }

        return new DomainMessageRouteCollection($routes);
    }
}
