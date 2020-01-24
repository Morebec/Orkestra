<?php


namespace Morebec\Orkestra\Messaging\Event;

/**
 * Interface EventSubscriberInterface
 * An Event subscriber similarly tio an EventHandler is responsible for reacting to events dispatched through an event bus.
 * Unlike an EventHandler, An EventSubscriber is used to listen to multiple events instead of a single one.
 */
interface EventSubscriberInterface
{
    /**
     * Returns an array of event listening definitions.
     * An Event listening definition is structured as follow
     * ['name of the event', 'name of the method that should handle the given event']
     * @return array<array<string, string>>
     */
    public static function getSubscribedEvents(): array;
}
