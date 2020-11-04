<?php

namespace Morebec\Orkestra\EventSourcing\Testing;

use Morebec\Orkestra\EventSourcing\Modeling\EventSourcedAggregateRoot;
use Morebec\Orkestra\Messaging\Event\DomainEventInterface;
use Morebec\Orkestra\Modeling\DomainEventCollectionInterface;

class EventSourcedAggregateRootTestScenario
{
    /**
     * @var EventSourcedAggregateRoot
     */
    private $aggregateRoot;

    /**
     * @var callable
     */
    private $whenCallable;

    /**
     * @var DomainEventInterface[]
     */
    private $givenEvents;

    private function __construct(EventSourcedAggregateRoot $aggregateRoot)
    {
        $this->aggregateRoot = $aggregateRoot;
    }

    public static function test(EventSourcedAggregateRoot $aggregateRoot): self
    {
        return new self($aggregateRoot);
    }

    public function given(array $domainEvents): self
    {
        $this->givenEvents = $domainEvents;

        return $this;
    }

    public function when(callable $whenCallable): self
    {
        $this->whenCallable = $whenCallable;

        return $this;
    }

    public function then(): DomainEventCollectionInterface
    {
        foreach ($this->givenEvents as $domainEvent) {
            $this->aggregateRoot->recordDomainEvent($domainEvent);
        }
        $this->aggregateRoot->clearDomainEvents();

        $when = $this->whenCallable;
        $when($this->aggregateRoot);

        return $this->aggregateRoot->getDomainEvents();
    }
}
