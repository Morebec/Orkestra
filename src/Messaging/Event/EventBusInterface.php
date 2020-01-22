<?php


namespace Morebec\Orkestra\Messaging\Event;

/**
 * Interface for event buses.
 * The event bus is responsible for dispatching an event to the right event handlers.
 */
interface EventBusInterface
{
    /**
     * Dispatches the event to the right event handlers.
     * @param EventInterface $event
     * @return mixed
     */
    public function dispatch(EventInterface $event): void;
}
