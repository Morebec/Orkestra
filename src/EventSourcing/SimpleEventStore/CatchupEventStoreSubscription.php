<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\EventStore\CatchupEventStoreSubscriptionInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreSubscriptionIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamIdInterface;

class CatchupEventStoreSubscription extends AbstractEventStoreSubscription implements CatchupEventStoreSubscriptionInterface
{
    /**
     * @var EventIdInterface
     */
    private $lastEventId;

    public function __construct(
        EventStoreSubscriptionIdInterface $subscriptionId,
        EventStreamIdInterface $streamId,
        array $typeFilter = [],
        ?EventIdInterface $lastEventId = null
    ) {
        parent::__construct($subscriptionId, $streamId, $typeFilter);
        $this->lastEventId = $lastEventId;
    }

    public function getLastEventId(): ?EventIdInterface
    {
        return $this->lastEventId;
    }
}
