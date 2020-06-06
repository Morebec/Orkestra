<?php

namespace Morebec\Orkestra\EventSourcing\Testing;

use Morebec\Orkestra\EventSourcing\AbstractEventSourcedAggregateRoot;

class EventSourcedAggregateRootTestScenario
{
    /**
     * @var array
     */
    private $givenEvents;

    /**
     * @var \Closure
     */
    private $whenClosure;

    /**
     * @var \Closure
     */
    private $thenClosure;

    /**
     * @var AbstractEventSourcedAggregateRoot
     */
    private $aggregateRoot;

    private function __construct(AbstractEventSourcedAggregateRoot $aggregateRoot)
    {
        $this->aggregateRoot = $aggregateRoot;
    }

    public static function for(AbstractEventSourcedAggregateRoot $aggregateRoot): self
    {
        return new self($aggregateRoot);
    }

    public function given(array $events): self
    {
        $this->givenEvents = $events;

        return $this;
    }

    public function when(\Closure $closure): self
    {
        $this->whenClosure = $closure;

        return $this;
    }

    public function then(\Closure $closure)
    {
        $this->thenClosure = $closure;

        $this->run();
    }

    private function run(): void
    {
        $aggregate = $this->aggregateRoot;
        $aggregate->loadFromHistory($this->givenEvents, $aggregate->getVersion());
        ($this->whenClosure)($aggregate);
        ($this->thenClosure)($aggregate);
    }
}
