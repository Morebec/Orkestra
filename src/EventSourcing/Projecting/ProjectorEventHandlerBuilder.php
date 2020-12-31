<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

class ProjectorEventHandlerBuilder
{
    /**
     * @var ProjectorEventHandlersMapBuilder
     */
    private $parent;

    /**
     * @var ProjectorEventHandlersMap
     */
    private $eventHandlersMap;

    /**
     * @var callable
     */
    private $eventFilter;

    /**
     * @var callable
     */
    private $callable;

    /**
     * @var callable|null
     */
    private $exceptionHandler;
    /**
     * @var string
     */
    private $eventClass;

    /**
     * @var ProjectorEventHandler|null
     */
    private $eventHandler;
    /**
     * @var ProjectionRepositoryInterface|null
     */
    private $repository;

    public function __construct(
        ProjectorEventHandlersMapBuilder $parent,
        ProjectorEventHandlersMap $eventHandlersMap,
        string $eventClass,
        ?ProjectionRepositoryInterface $repository
    ) {
        $this->parent = $parent;
        $this->eventHandlersMap = $eventHandlersMap;

        $this->eventFilter = null;
        $this->callable = null;
        $this->exceptionHandler = null;
        $this->eventClass = $eventClass;

        $this->eventHandler = null;
        $this->repository = $repository;
    }

    public function when(callable $filter): self
    {
        $this->eventFilter = $filter;

        return $this;
    }

    /**
     * Allows to define an event handler that creates a projection instance to be saved in a repository.
     */
    public function createAs(callable $buildProjection): CreateProjectionEventHandlerBuilder
    {
        return new CreateProjectionEventHandlerBuilder(
            $this->parent,
            $this->eventHandlersMap,
            $this->eventClass,
            $buildProjection,
            $this->eventFilter,
            $this->exceptionHandler,
            $this->repository
        );
    }

    /**
     * Allows to define an event handler that updates a projection instance to be saved in a repository.
     */
    public function updateAs(callable $updateProjectionCallable): UpdateProjectionEventHandlerBuilder
    {
        return new UpdateProjectionEventHandlerBuilder(
            $this->parent,
            $this->eventHandlersMap,
            $this->eventClass,
            $updateProjectionCallable,
            $this->eventFilter,
            $this->exceptionHandler,
            $this->repository
        );
    }

    /**
     * Allows to define an event handler that deletes a projection from a configured repository
     * having a given id.
     *
     * @return $this
     */
    public function deleteWhereId(callable $getIdCallable): DeleteProjectionEventHandlerBuilder
    {
        return new DeleteProjectionEventHandlerBuilder(
            $this->parent,
            $this->eventHandlersMap,
            $this->eventClass,
            $getIdCallable,
            $this->eventFilter,
            $this->exceptionHandler,
            $this->repository
        );
    }

    /**
     * Allow to define the callable that will apply the logic of the event handler.
     * This method should always be called after {@link self::onException()} or {@link self::when()}.
     */
    public function as(callable $c): ProjectorEventHandlersMapBuilder
    {
        $this->callable = $c;

        $this->eventHandler = new ProjectorEventHandler($c, $this->eventFilter, $this->exceptionHandler);

        $this->eventHandlersMap->addEventHandler($this->eventClass, $this->eventHandler);

        return $this->parent;
    }

    public function onException(callable $exceptionHandler): self
    {
        $this->exceptionHandler = $exceptionHandler;

        return $this;
    }
}
