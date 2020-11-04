<?php

namespace Morebec\Orkestra\EventSourcing\Modeling;

use Morebec\Orkestra\Messaging\Event\DomainEventInterface;
use Morebec\Orkestra\Modeling\DomainEventCollection;
use Morebec\Orkestra\Modeling\EventEmittingAggregateRoot;

/**
 * Implementation of an Event Sourced Aggregate Root.
 */
abstract class EventSourcedAggregateRoot extends EventEmittingAggregateRoot
{
    /**
     * Reloads this aggregate root from history.
     *
     * @return static
     */
    public static function loadFromHistory(DomainEventCollection $events): self
    {
        $a = new static();
        foreach ($events->toArray() as $event) {
            $a->onDomainEvent($event);
        }

        return $a;
    }

    public function recordDomainEvent(DomainEventInterface $event): void
    {
        parent::recordDomainEvent($event);

        // Apply the event.
        $this->onDomainEvent($event);
    }

    /**
     * Method called to apply a domain event to this aggregate.
     */
    abstract protected function onDomainEvent(DomainEventInterface $event): void;
}
