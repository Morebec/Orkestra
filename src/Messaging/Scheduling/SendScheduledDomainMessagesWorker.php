<?php

namespace Morebec\Orkestra\Messaging\Scheduling;

use Morebec\Orkestra\DateTime\ClockInterface;
use Morebec\Orkestra\Messaging\DomainMessageBusInterface;
use Morebec\Orkestra\Messaging\DomainResponseStatusCode;
use Morebec\Orkestra\Worker\AbstractWorker;

/**
 * This worker is responsible for continuously sending scheduled domain messages back to the bus.
 */
class SendScheduledDomainMessagesWorker extends AbstractWorker
{
    /**
     * @var DomainMessageSchedulerStorageInterface
     */
    private $schedulerStorage;

    /**
     * @var ClockInterface
     */
    private $clock;

    /**
     * @var DomainMessageBusInterface
     */
    private $messageBus;

    public function __construct(
        ClockInterface $clock,
        DomainMessageSchedulerStorageInterface $schedulerStorage,
        DomainMessageBusInterface $messageBus,
        SendScheduledDomainMessagesWorkerOptions $options,
        iterable $watchers = []
    ) {
        parent::__construct($options, $watchers);

        $this->schedulerStorage = $schedulerStorage;
        $this->clock = $clock;
        $this->messageBus = $messageBus;
    }

    protected function executeTask(): void
    {
        /** @var SendScheduledDomainMessagesWorkerOptions $options */
        $options = $this->options;
        $scheduledDomainMessageWrappers = $this->schedulerStorage->findScheduledBefore($this->clock->now());

        foreach ($scheduledDomainMessageWrappers as $scheduledDomainMessageWrapper) {
            $exception = null;
            for ($i = 0; $i <= $options->maxNumberRetries; $i++) {
                $response = $this->messageBus->sendMessage($scheduledDomainMessageWrapper->getMessage());

                if (!$response->getStatusCode()->isEqualTo(DomainResponseStatusCode::FAILED())) {
                    break;
                }

                $exception = $response->getPayload();
            }
        }
    }
}
