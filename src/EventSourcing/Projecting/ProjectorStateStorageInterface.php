<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

/**
 * Used by the projectionist to store the current state of a projector.
 */
interface ProjectorStateStorageInterface
{
    /**
     * Marks a projector as broken at a given event Id.
     */
    public function markBroken(ProjectorInterface $projector, string $eventId): void;

    /**
     * Marks a projector as being in the process of booting.
     */
    public function markBooting(ProjectorInterface $projector): void;

    /**
     * Marks a projector as being booted.
     */
    public function markBooted(ProjectorInterface $projector): void;

    /**
     * Marks a projector as being currently running.
     */
    public function markRunning(ProjectorInterface $projector): void;

    /**
     * Marks a projector as being shutdown.
     */
    public function markShutdown(ProjectorInterface $projector): void;
}
