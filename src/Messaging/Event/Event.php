<?php

namespace Morebec\Orkestra\Messaging\Event;

use Morebec\DateTime\DateTime;
use Morebec\DateTime\SystemClock;

/**
 * Event implementation providing a timestamp indicating when a given event occured.
 */
class Event implements EventInterface
{
    /** @var DateTime */
    public $occurredAt;

    public function __construct()
    {
        $this->occurredAt = SystemClock::now();
    }
}
