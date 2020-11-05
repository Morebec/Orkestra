<?php

namespace Morebec\Orkestra\Messaging\Event;

use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * Represents a event message. Event Messages are used to communicate
 * that something meaningful has happened.
 */
interface DomainEventInterface extends DomainMessageInterface
{
}
