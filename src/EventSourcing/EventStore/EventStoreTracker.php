<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

use EmptyIterator;

/**
 * Event store tracker is responsible for tracking the progress of an Event Handler/Workflow reading the
 * Event Store in order.
 * The progress of reading is called a TrackingUnit.
 * TrackingUnits have a unique Id and a last read event id reference.
 */
class EventStoreTracker
{
    /**
     * @var EventStoreInterface
     */
    private $eventStore;

    /**
     * @var EventStoreTrackingUnitRepositoryInterface
     */
    private $trackingUnitRepository;

    /**
     * EventStoreTracker constructor.
     */
    public function __construct(
        EventStoreInterface $eventStore,
        EventStoreTrackingUnitRepositoryInterface $trackingUnitRepository
    ) {
        $this->eventStore = $eventStore;
        $this->trackingUnitRepository = $trackingUnitRepository;
    }

    /**
     * Resets a given tracking unit with id.
     * If it cannot be found throw an exception.
     */
    public function resetFor(string $trackingUnitId): void
    {
        $trackingUnit = $this->trackingUnitRepository->findById($trackingUnitId);
        if (!$trackingUnit) {
            // No need to do anything. In any case if this non existing tracking unit is replayed, it will
            // implicitly be created.
            return;
        }

        $trackingUnit->reset();
        $this->trackingUnitRepository->update($trackingUnit);
    }

    /**
     * Plays next event in the stream for a given tracking unit id.
     * If the tracking unit can't be found, it will be created and tracked from the beginning.
     *
     * @param string $trackingUnitId id of the tracking unit
     */
    public function replayFor(string $trackingUnitId): iterable
    {
        $trackingUnit = $this->trackingUnitRepository->findById($trackingUnitId);

        if (!$trackingUnit) {
            $trackingUnit = EventStoreTrackingUnit::create($trackingUnitId);
        }

        $lastReadEventId = $trackingUnit->getLastReadEventId();

        if ($lastReadEventId === null) {
            $events = $this->eventStore->readAllFromTimestampForward(0);
        } else {
            $events = $this->eventStore->readAllFromEventIdForward($lastReadEventId, false);
        }

        if (!$events) {
            return new EmptyIterator();
        }

        /** @var EventDescriptor $event */
        foreach ($events as $event) {
            yield $event;
            $trackingUnit->changeLastReadEventId($event->eventId);
            $this->trackingUnitRepository->update($trackingUnit);
        }
    }
}
