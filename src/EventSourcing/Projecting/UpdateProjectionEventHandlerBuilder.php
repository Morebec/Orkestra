<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

class UpdateProjectionEventHandlerBuilder
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
     * @var string
     */
    private $eventClass;

    /**
     * @var callable
     */
    private $callable;
    /**
     * @var callable|null
     */
    private $predicate;
    /**
     * @var callable|null
     */
    private $onException;

    /**
     * @var UpdateProjectionEventHandler
     */
    private $handler;
    /**
     * @var ProjectionRepositoryInterface|null
     */
    private $repository;

    public function __construct(
        ProjectorEventHandlersMapBuilder $parent,
        ProjectorEventHandlersMap $eventHandlersMap,
        string $eventClass,
        callable $callable,
        ?callable $predicate = null,
        ?callable $onException = null,
        ?ProjectionRepositoryInterface $repository = null
    ) {
        $this->parent = $parent;
        $this->eventHandlersMap = $eventHandlersMap;
        $this->eventClass = $eventClass;
        $this->callable = $callable;
        $this->predicate = $predicate;
        $this->onException = $onException;
        $this->repository = $repository;
    }

    public function withId(callable $getIdCallable): self
    {
        $this->handler = new UpdateProjectionEventHandler(
            $getIdCallable,
            $this->callable,
            $this->predicate,
            $this->onException
        );

        if ($this->repository) {
            $this->handler->setRepository($this->repository);
        }

        $this->eventHandlersMap->addEventHandler($this->eventClass, $this->handler);

        return $this;
    }

    public function usingRepository(ProjectionRepositoryInterface $repository): self
    {
        if (!$this->handler) {
            throw new ProjectionException('You must call withId before setting up the repository.');
        }

        $this->handler->setRepository($repository);

        return $this;
    }
}
