<?php

namespace Morebec\Orkestra\Modeling;

use InvalidArgumentException;
use Morebec\Orkestra\Messaging\Event\DomainEventInterface;

class DomainEventCollection implements DomainEventCollectionInterface, \Countable
{
    /**
     * @var DomainEventInterface[]
     */
    private $events;

    /**
     * DomainEventCollection constructor.
     *
     * @param DomainEventInterface[] $domainEvents
     */
    public function __construct(iterable $domainEvents = [])
    {
        $this->events = [];
        foreach ($domainEvents as $domainEvent) {
            $this->add($domainEvent);
        }
    }

    public function add(DomainEventInterface $event): void
    {
        $this->events[] = $event;
    }

    public function remove(DomainEventInterface $event): void
    {
        $nbEvents = \count($this->events);
        $this->events = $this->filter(static function ($e) use ($event) {
            return $e !== $event;
        })->toArray();

        if ($nbEvents === \count($this->events)) {
            throw new InvalidArgumentException('Domain Event was not found in collection.');
        }
    }

    public function clear(): void
    {
        $this->events = [];
    }

    public function ofType(string $eventClass): DomainEventCollectionInterface
    {
        return $this->filter(static function (DomainEventInterface $event) use ($eventClass) {
            return is_a($event, $eventClass, true) || $event::getTypeName() === $eventClass;
        });
    }

    public function filter(callable $predicate): DomainEventCollectionInterface
    {
        $filtered = array_filter($this->events, $predicate);

        return new static($filtered);
    }

    public function toArray(): array
    {
        return $this->events;
    }

    public function isEmpty(): bool
    {
        return $this->getCount() === 0;
    }

    public function getLast(): ?DomainEventInterface
    {
        if ($this->isEmpty()) {
            return null;
        }

        $nbEvents = \count($this->events);

        return $this->events[$nbEvents - 1];
    }

    public function getLastOfType(string $eventClass): ?DomainEventInterface
    {
        return $this->ofType($eventClass)->getLast();
    }

    public function getFirst(): ?DomainEventInterface
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->events[0];
    }

    public function getFirstOfType(string $eventClass): ?DomainEventInterface
    {
        return $this->ofType($eventClass)->getFirst();
    }

    public function copy(): DomainEventCollectionInterface
    {
        return new static($this->events);
    }

    public function getCount(): int
    {
        return \count($this->events);
    }

    public function current()
    {
        return current($this->events);
    }

    public function next()
    {
        return next($this->events);
    }

    public function key()
    {
        return key($this->events);
    }

    public function valid()
    {
        return \array_key_exists($this->key(), $this->events);
    }

    public function rewind()
    {
        return reset($this->events);
    }

    public function count()
    {
        return $this->getCount();
    }
}
