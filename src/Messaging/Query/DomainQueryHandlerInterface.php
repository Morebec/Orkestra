<?php

namespace Morebec\Orkestra\Messaging\Query;

use Morebec\Orkestra\Messaging\DomainMessageHandlerInterface;

/**
 * Represents a message handler specialized in messages of type {@link DomainQueryInterface}.
 */
interface DomainQueryHandlerInterface extends DomainMessageHandlerInterface
{
}
