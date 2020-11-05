<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

/**
 * A Projectionist is responsible for operating projectors.
 */
interface ProjectionistInterface
{
    /**
     * Replays a given projector from the start.
     */
    public function replayProjector(ProjectorInterface $projector): void;

    /**
     * Boots a given projector.
     */
    public function bootProjector(ProjectorInterface $projector): void;

    /**
     * Shuts down a projector after successfully having processed what it needed.
     */
    public function shutdownProjector(ProjectorInterface $projector): void;

    /**
     * Runs a given projector by first booting it up, running it and then shutting shutting it down.
     */
    public function runProjector(ProjectorInterface $projector): void;

    /**
     * Resets a given projector.
     */
    public function resetProjector(ProjectorInterface $projector): void;
}
