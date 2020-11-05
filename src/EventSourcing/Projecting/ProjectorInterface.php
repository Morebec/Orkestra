<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

use Morebec\Orkestra\Messaging\Event\DomainEventHandlerInterface;
use Morebec\Orkestra\Messaging\Event\DomainEventInterface;

/**
 * Projectors are responsible for projecting events of write models into read models.
 * It follows a projection metaphor in cinemas: A Projector is a tool used to transform movie rolls
 * into pictures on a screen frame by frame.
 */
interface ProjectorInterface extends DomainEventHandlerInterface
{
    /**
     * Called right before the projector is operated by the projectionist.
     */
    public function boot(): void;

    /**
     * Projects an event.
     */
    public function project(DomainEventInterface $event): void;

    /**
     * Called when the projectionist is done with the projector.
     */
    public function shutdown(): void;

    /**
     * Resets the projector's projection to a clean slate.
     */
    public function reset(): void;

    /**
     * Returns the type name of the projector.
     * This allows referencing projectors with their name.
     */
    public static function getTypeName(): string;
}
