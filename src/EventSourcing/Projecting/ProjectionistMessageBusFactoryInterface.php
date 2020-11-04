<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

use Morebec\Orkestra\Messaging\DomainMessageBusInterface;

/**
 * Interface responsible for the contract of creating a message bus tailored for the needs of a Projectionist.
 */
interface ProjectionistMessageBusFactoryInterface
{
    /**
     * Builds a {@link DomainMessageBusInterface} according to this factory's implementation.
     */
    public function build(): DomainMessageBusInterface;
}
