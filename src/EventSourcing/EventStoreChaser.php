<?php

namespace Morebec\Orkestra\EventSourcing;

use Morebec\Orkestra\EventSourcing\EventStore\EventDescriptor;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreTracker;
use Morebec\Orkestra\Messaging\Event\EventBusInterface;

/**
 * Used to long poll the event store for new events to be dispatched.
 * Relies on the Event bus to dispatch the events to the right handlers.
 */
class EventStoreChaser
{
    private const STREAM_ID = 'event_store_chaser';
    /**
     * @var EventStoreTracker
     */
    private $eventStoreTracker;
    /**
     * @var EventBusInterface
     */
    private $eventBus;

    public function __construct(
        EventStoreTracker $eventStoreTracker,
        EventBusInterface $eventBus
    ) {
        $this->eventStoreTracker = $eventStoreTracker;
        $this->eventBus = $eventBus;
    }

    public function process(): void
    {
        $events = $this->eventStoreTracker->replayFor(self::STREAM_ID);
        /** @var EventDescriptor $eventDescriptor */
        foreach ($events as $eventDescriptor) {
            $this->eventBus->dispatch($eventDescriptor->payload);
        }
    }
}
