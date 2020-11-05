<?php

namespace Morebec\Orkestra\Messaging\Query;

use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * Interface representing a Command Message.
 * Commands a used to request a certain action be taken by some unit.
 */
interface DomainQueryInterface extends DomainMessageInterface
{
}
