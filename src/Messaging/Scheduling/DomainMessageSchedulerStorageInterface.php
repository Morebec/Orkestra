<?php

namespace Morebec\Orkestra\Messaging\Scheduling;

use Morebec\Orkestra\DateTime\DateTime;

interface DomainMessageSchedulerStorageInterface
{
    /**
     * Adds a Wrapped Domain Message to the storage.
     */
    public function add(ScheduledDomainMessageWrapper $wrappedMessage): void;

    /**
     * Returns Domain Messages that were scheduled before a given datetime.
     *
     * @return ScheduledDomainMessageWrapper[]
     */
    public function findScheduledBefore(DateTime $dateTime): array;

    /**
     * Returns Domain Message that where previously stored
     * and scheduled between a given range of date times (inclusively).
     *
     * @return ScheduledDomainMessageWrapper[]
     */
    public function findByDateTime(DateTime $from, DateTime $to): array;

    /**
     * Removes a Scheduled Domain Message From this store from this storage.
     */
    public function remove(ScheduledDomainMessageWrapper $message): void;
}
