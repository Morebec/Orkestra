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

    /**
     * Indicates if a given projector is broken or not.
     */
    public function isBroken(ProjectorInterface $projector): bool;

    /**
     * Indicates if a given projector is currently running or not.
     */
    public function isRunning(ProjectorInterface $projector): bool;
}
