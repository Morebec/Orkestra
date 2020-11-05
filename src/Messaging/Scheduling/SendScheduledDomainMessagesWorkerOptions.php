<?php

namespace Morebec\Orkestra\Messaging\Scheduling;

use Morebec\Orkestra\Worker\WorkerOptions;

/**
 * Options for the SendScheduledDomainMessagesWorker.
 */
class SendScheduledDomainMessagesWorkerOptions extends WorkerOptions
{
    /**
     * The maximum number of retries to send the message back on the bus
     * that should be performed when an domain response has a status code failing.
     *
     * @var int
     */
    public $maxNumberRetries = 0;
}
