<?php

namespace Morebec\Orkestra\Messaging\Scheduling;

use Morebec\Orkestra\DateTime\ClockInterface;
use Morebec\Orkestra\Messaging\DomainMessageBusInterface;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * Default Implementation of a Domain Message Scheduler.
 */
class DomainMessageScheduler implements DomainMessageSchedulerInterface
{
    /**
     * @var DomainMessageSchedulerStorageInterface
     */
    private $storage;

    /**
     * @var ClockInterface
     */
    private $clock;

    /**
     * @var DomainMessageBusInterface
     */
    private $domainMessageBus;

    public function __construct(
        ClockInterface $clock,
        DomainMessageSchedulerStorageInterface $storage,
        DomainMessageBusInterface $domainMessageBus
    ) {
        $this->storage = $storage;
        $this->clock = $clock;
        $this->domainMessageBus = $domainMessageBus;
    }

    public function schedule(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers): void
    {
        $wrapper = ScheduledDomainMessageWrapper::wrap($domainMessage, $headers);
        $this->storage->add($wrapper);
    }

    public function processScheduledMessages(): void
    {
        $scheduledMessages = $this->storage->findScheduledBefore($this->clock->now());

        foreach ($scheduledMessages as $message) {
            $headers = $message->getMessageHeaders();

            // To avoid the message getting rescheduled.
            $headers->set(DomainMessageHeaders::SCHEDULED_AT, null);

            $this->domainMessageBus->sendMessage($message->getMessage(), $headers);
            $this->storage->remove($message);
        }
    }
}
