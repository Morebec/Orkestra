<?php

namespace Morebec\Orkestra\Messaging\Command;

use Morebec\Orkestra\Messaging\DomainMessageHandlerInterface;

/**
 * Represents a message handler specialized in messages of type {@link DomainCommandInterface}.
 */
interface DomainCommandHandlerInterface extends DomainMessageHandlerInterface
{
}
