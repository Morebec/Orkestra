<?php

namespace Morebec\Orkestra\Messaging\Event;

/**
 * Interface EventSubscriberInterface
 * Similarly to an EventHandler, an Event Subscriber is responsible for reacting to events dispatched through an event bus.
 * Unlike an EventHandler, An EventSubscriber is used to listen to multiple events instead of a single one.
 * Event Handling method should be constructed as follows:
 *  - Single type hinted argument of type EventInterface.
 */
interface EventSubscriberInterface
{
}
