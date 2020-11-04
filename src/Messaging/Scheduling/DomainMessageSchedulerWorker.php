<?php

namespace Morebec\Orkestra\Messaging\Scheduling;

use Morebec\Orkestra\DateTime\ClockInterface;
use Morebec\Orkestra\Messaging\DomainMessageBusInterface;
use Morebec\Orkestra\Worker\AbstractWorker;
use Morebec\Orkestra\Worker\WorkerOptions;

/**
 * Worker responsible for sending scheduled messages to the DomainMessageBus.
 */
class DomainMessageSchedulerWorker extends AbstractWorker
{
    /**
     * @var DomainMessageBusInterface
     */
    private $domainMessageBus;
    /**
     * @var DomainMessageSchedulerStorageInterface
     */
    private $schedulerStorage;
    /**
     * @var ClockInterface
     */
    private $clock;
    /**
     * @var DomainMessageSchedulerInterface
     */
    private $scheduler;

    public function __construct(
        ClockInterface $clock,
        DomainMessageSchedulerInterface $scheduler,
        DomainMessageBusInterface $domainMessageBus,
        DomainMessageSchedulerStorageInterface $schedulerStorage,
        ?WorkerOptions $options = null,
        iterable $watchers = []
    ) {
        parent::__construct($options ?: new WorkerOptions(), $watchers);
        $this->clock = $clock;
        $this->domainMessageBus = $domainMessageBus;
        $this->schedulerStorage = $schedulerStorage;
        $this->scheduler = $scheduler;
    }

    protected function executeTask(): void
    {
        $this->scheduler->processScheduledMessages();
    }
}
