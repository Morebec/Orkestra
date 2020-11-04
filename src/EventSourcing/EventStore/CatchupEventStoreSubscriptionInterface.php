<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

interface CatchupEventStoreSubscriptionInterface extends EventStoreSubscriptionInterface
{
    /**
     * Returns the last event ID that was processed by this subscription or null if none was read.
     */
    public function getLastEventId(): ?EventIdInterface;
}
