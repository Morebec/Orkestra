<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

use Morebec\Orkestra\EventSourcing\EventStore\EventStoreTrackingUnit;

interface EventStoreTrackingUnitRepositoryInterface
{
    /**
     * Updates a given tracker in this repository.
     * If the tracker does not exists in the repository, this method will add it
     * first and then update its status.
     *
     * @return mixed
     */
    public function update(EventStoreTrackingUnit $trackingUnit);

    /**
     * Finds a tracking unit by its id and returns it or returns null if it was not found.
     */
    public function findById(string $trackingUnitId): ?EventStoreTrackingUnit;
}
