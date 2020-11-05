<?php

namespace Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\DomainMessageHandlerInterface;
use Psr\Container\ContainerInterface;

/**
 * Implementation of a {@link DomainMessageHandlerProviderInterface} fetching the {@link DomainMessageHandlerInterface}
 * from a PSR-4 Container.
 */
class ContainerDomainMessageHandlerProvider implements DomainMessageHandlerProviderInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getDomainMessageHandler(string $domainMessageHandlerClassName): ?DomainMessageHandlerInterface
    {
        if (!$this->container->has($domainMessageHandlerClassName)) {
            return null;
        }

        return $this->container->get($domainMessageHandlerClassName);
    }
}
