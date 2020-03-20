<?php

namespace Morebec\Orkestra\Messaging\Event;

/**
 * An Event Handler handler is responsible for handling an event that was dispatched through the event bus.
 * There can be many different handlers for a single event, or none.
 * To implement this interface, create an __invoke method taking a specific EventInterface as a parameter.
 *
 * @template T of EventInterface
 */
interface EventHandlerInterface
{
}
