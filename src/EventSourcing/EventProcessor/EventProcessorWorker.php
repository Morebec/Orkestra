<?php

namespace Morebec\Orkestra\EventSourcing\EventProcessor;

use Morebec\Orkestra\EventSourcing\EventStore\CatchupEventStoreSubscriptionInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreInterface;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\CatchupEventStoreSubscription;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\DomainEventDescriptor;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventStoreSubscriptionId;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\SimpleEventStore;
use Morebec\Orkestra\Messaging\DomainMessageBusInterface;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Worker\AbstractWorker;
use Morebec\Orkestra\Worker\WorkerOptions;

/**
 * The Event Processor Worker is responsible for reading new events in the event store and dispatching them to their
 * corresponding handlers in a fire and forget fashion.
 * Every Event Handler is responsible for its own handling and should be able to recover from errors.
 */
class EventProcessorWorker extends AbstractWorker
{
    public const EVENT_STORE_SUBSCRIPTION_ID = 'event_processor';

    /**
     * @var EventStoreInterface
     */
    private $eventStore;
    /**
     * @var DomainMessageBusInterface
     */
    private $domainMessageBus;

    public function __construct(
        DomainMessageBusInterface $domainMessageBus,
        EventStoreInterface $eventStore,
        ?WorkerOptions $options = null,
        iterable $watchers = []
    ) {
        parent::__construct($options ?: new WorkerOptions(), $watchers);
        $this->eventStore = $eventStore;
        $this->domainMessageBus = $domainMessageBus;
    }

    protected function executeTask(): void
    {
        $subscriptionId = EventStoreSubscriptionId::fromString(self::EVENT_STORE_SUBSCRIPTION_ID);
        $subscription = $this->eventStore->getSubscription($subscriptionId);
        if (!$subscription) {
            $this->eventStore->startSubscription(new CatchupEventStoreSubscription(
                $subscriptionId,
                SimpleEventStore::getGlobalStreamId()
            ));
        }

        $subscription = $this->eventStore->getSubscription($subscriptionId);
        if (!$subscription) {
            throw new \LogicException('Subscription not found for Event Processor.');
        }

        if (!$subscription instanceof CatchupEventStoreSubscriptionInterface) {
            throw new \LogicException('Invalid subscription type for Event Processor.');
        }

        $events = $this->eventStore->readStreamForward($subscription->getStreamId(), $subscription->getLastEventId());

        /** @var DomainEventDescriptor $eventDescriptor */
        foreach ($events as $eventDescriptor) {
            $metadata = $eventDescriptor->getEventMetadata()->toArray();
            $this->domainMessageBus->sendMessage($eventDescriptor->getEvent(), new DomainMessageHeaders([
                DomainMessageHeaders::MESSAGE_ID => (string) $eventDescriptor->getEventId(),
                DomainMessageHeaders::CORRELATION_ID => $metadata['correlationId'],
                DomainMessageHeaders::CAUSATION_ID => $metadata['causationId'],
                DomainMessageHeaders::TENANT_ID => $metadata['tenantId'],
            ]));

            // NOTE: We don't handle errors here, not our job. The message bus can already log these.

            $this->eventStore->advanceSubscription($subscriptionId, $eventDescriptor->getEventId());
        }
    }
}
