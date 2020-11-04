<?php

namespace Morebec\Orkestra\EventSourcing;

use Morebec\Orkestra\Messaging\Event\EventInterface;
use Morebec\Orkestra\Modeling\AggregateRootIdentifierInterface;
use Morebec\Orkestra\Modeling\AggregateRootInterface;

/**
 * Implementation of an Aggregate Root for use with Event sourcing.
 */
abstract class AbstractEventSourcedAggregateRoot implements AggregateRootInterface
{
    /**
     * Initial version number indicating the aggregate is new and has not undergone any change events.
     * Usually the creation event will bump this number to 0. Meaning a newly created aggregate root with
     * a creation event will always have a version of 0.
     *
     *  @var int
     */
    public const INITIAL_VERSION = -1;

    /**
     * @var AggregateRootIdentifierInterface
     */
    protected $id;

    /**
     * List of uncommitted changes.
     *
     * @var array<EventInterface>
     */
    private $changes = [];

    /**
     * Version of this aggregate root.
     * This version number is updated every time a new uncommitted change is applied on an aggregate root.
     * This is used to ensure consistency with concurrent writes.
     *
     * @var int Aggregate root version
     */
    private $version = -1;

    /**
     * Returns the version number of this aggregate.
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * Returns the uncommitted changes of this Aggregate Root.
     *
     * @return array<EventInterface>
     */
    public function getUncommittedChanges(): array
    {
        return $this->changes;
    }

    /**
     * Marks the changes of this Aggregate Root as committed.
     * A Call to this method would cause the method getUncommittedChanges to return an empty list.
     */
    public function markChangesAsCommitted(): void
    {
        $this->changes = [];
    }

    /**
     * Loads this aggregate from a history of events.
     *
     * @param array<EventInterface> $history
     */
    public function loadFromHistory(array $history, int $version): void
    {
        $this->version = $version;
        foreach ($history as $event) {
            $this->recordChange($event);
        }
        $this->markChangesAsCommitted();
    }

    /**
     * Applies an event change.
     */
    abstract protected function applyChange(EventInterface $event): void;

    /**
     * Records an event in the list of uncommitted changes.
     */
    protected function recordChange(EventInterface $event): void
    {
        $this->changes[] = $event;
        $this->applyChange($event);
    }
}
