<?php

namespace Morebec\Orkestra\Messaging\Event;

use Morebec\DateTime\DateTime;
use Morebec\DateTime\SystemClock;

/**
 * Abstract Event implementation providing a timestamp indicating
 * when a given event occurred.
 */
class AbstractEvent implements EventInterface
{
    /** @var DateTime */
    public $occurredAt;

    public function __construct()
    {
        $this->occurredAt = SystemClock::now();
    }
}
