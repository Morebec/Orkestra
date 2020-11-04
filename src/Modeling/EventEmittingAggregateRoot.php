<?php

namespace Morebec\Orkestra\Modeling;

use Morebec\Orkestra\Messaging\Event\DomainEventInterface;

class EventEmittingAggregateRoot implements AggregateRootInterface
{
    /** @var DomainEventCollectionInterface */
    private $domainEvents;

    public function __construct()
    {
        $this->domainEvents = new DomainEventCollection();
    }

    /**
     * Clears the list of domain events.
     */
    public function clearDomainEvents(): void
    {
        $this->domainEvents->clear();
    }

    public function getDomainEvents(): DomainEventCollectionInterface
    {
        return $this->domainEvents->copy();
    }

    /**
     * Adds an event to the list of uncommitted changes.
     */
    public function recordDomainEvent(DomainEventInterface $event): void
    {
        $this->domainEvents->add($event);
    }
}
