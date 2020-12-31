<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

/**
 * Represents a Mapping between {@link DomainEventMessage} and {@link ProjectorEventHandler}.
 */
class ProjectorEventHandlersMap
{
    /** @var ProjectorEventHandler[][] */
    private $handlers;

    public function __construct()
    {
        $this->handlers = [];
    }

    /**
     * Adds an event handler to this map.
     */
    public function addEventHandler(string $eventClassName, ProjectorEventHandler $handler): void
    {
        if (!\array_key_exists($eventClassName, $this->handlers)) {
            $this->handlers[$eventClassName] = [];
        }

        $this->handlers[$eventClassName][] = $handler;
    }

    /**
     * Returns the handlers that can handle a given domain message of a certain type.
     */
    public function getEventHandlersForEvent(string $eventClassName): array
    {
        $handlers = [];

        foreach ($this->handlers as $class => $handlers) {
            if (is_a($eventClassName, $class)) {
                foreach ($handlers as $handler) {
                    $handlers[] = $handler;
                }
            }
        }

        return $handlers;
    }
}
