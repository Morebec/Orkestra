<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

/**
 * Fluent Builder for {@link ProjectorEventHandlersMap}.
 */
class ProjectorEventHandlersMapBuilder
{
    /**
     * @var callable|null
     */
    private $eventFilter;

    /**
     * @var ProjectorEventHandlersMap
     */
    private $map;

    /**
     * @var ProjectionRepositoryInterface|null
     */
    private $repository;

    public function __construct()
    {
        $this->eventFilter = null;
        $this->map = new ProjectorEventHandlersMap();
    }

    /**
     * Predicate allowing to filter out events before they are processed.
     */
    public function where(callable $filter): self
    {
        $this->eventFilter = $filter;

        return $this;
    }

    /**
     * Allows to set a default repository to be used by CRUD event handlers.
     */
    public function usingRepository(ProjectionRepositoryInterface $repository): self
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Maps a certain event class.
     */
    public function map(string $eventClass): ProjectorEventHandlerBuilder
    {
        return new ProjectorEventHandlerBuilder($this, $this->map, $eventClass, $this->repository);
    }

    /**
     * Builds the map according to this configuration.
     */
    public function build(): ProjectorEventHandlersMap
    {
        return $this->map;
    }
}
