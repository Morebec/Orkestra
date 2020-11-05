<?php

namespace Morebec\Orkestra\Messaging\Event;

use Morebec\Orkestra\Messaging\DomainMessageHandlerInterface;

/**
 * Represents a message handler specialized in messages of type {@link DomainEventInterface}.
 */
interface DomainEventHandlerInterface extends DomainMessageHandlerInterface
{
}
