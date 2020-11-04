<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\EventStore\EventDescriptorInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\StreamedEventCollectionInterface;

/**
 * Simple Implementation of an Streamed Event Collection with events stored in an in-memory array.
 */
class StreamedEventCollection implements StreamedEventCollectionInterface
{
    /**
     * @var EventStreamIdInterface
     */
    private $streamId;

    /**
     * @var RecordedEventDescriptor[]
     */
    private $events;

    public function __construct(EventStreamIdInterface $streamId, array $events)
    {
        $this->events = [];
        foreach ($events as $event) {
            $this->add($event);
        }
        $this->streamId = $streamId;
    }

    public function build(EventStreamIdInterface $streamId, array $events): StreamedEventCollectionInterface
    {
        return new self($streamId, $events);
    }

    public function getFirst(): ?EventDescriptorInterface
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->events[0];
    }

    public function getLast(): ?EventDescriptorInterface
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->events[$this->getCount() - 1];
    }

    public function toArray(): array
    {
        return $this->events;
    }

    public function getEventStreamId(): EventStreamIdInterface
    {
        return $this->streamId;
    }

    public function getCount(): int
    {
        return \count($this->events);
    }

    public function isEmpty(): bool
    {
        return $this->getCount() === 0;
    }

    /**
     * @return EventDescriptorInterface
     */
    public function current()
    {
        return current($this->events);
    }

    /**
     * @return EventDescriptorInterface
     */
    public function next()
    {
        return next($this->events);
    }

    /**
     * @return int|null
     */
    public function key()
    {
        return key($this->events);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return \array_key_exists($this->key(), $this->events);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        reset($this->events);
    }

    /**
     * Adds an event to this collection.
     */
    private function add(EventDescriptorInterface $event): void
    {
        $this->events[] = $event;
    }
}
