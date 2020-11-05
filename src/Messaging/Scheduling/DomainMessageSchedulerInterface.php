<?php

namespace Morebec\Orkestra\Messaging\Scheduling;

use Morebec\Orkestra\Messaging\DomainMessageBusInterface;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * For interface for the service responsible for Scheduling Domain Messages for later dispatching.
 */
interface DomainMessageSchedulerInterface
{
    /**
     * Schedules a Message for later processing.
     */
    public function schedule(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers): void;

    /**
     * Finds all scheduled messages and processes them so they are sent on the {@link DomainMessageBusInterface}.
     */
    public function processScheduledMessages(): void;
}
