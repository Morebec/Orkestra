<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\EventStore\EventStoreSubscriptionIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreSubscriptionInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamIdInterface;

/**
 * Abstract Implementation of Event Store subscriptions.
 */
abstract class AbstractEventStoreSubscription implements EventStoreSubscriptionInterface
{
    /**
     * @var EventStoreSubscriptionIdInterface
     */
    private $id;
    /**
     * @var EventStreamIdInterface
     */
    private $streamId;
    /**
     * @var array
     */
    private $typeFilter;

    public function __construct(
        EventStoreSubscriptionIdInterface $subscriptionId,
        EventStreamIdInterface $streamId,
        array $typeFilter
    ) {
        $this->id = $subscriptionId;
        $this->streamId = $streamId;
        $this->typeFilter = $typeFilter;
    }

    public function getId(): EventStoreSubscriptionIdInterface
    {
        return $this->id;
    }

    public function getStreamId(): EventStreamIdInterface
    {
        return $this->streamId;
    }

    public function getTypeFilter(): array
    {
        return $this->typeFilter;
    }
}
