<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

class DeleteProjectionEventHandlerBuilder
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
    private $getIdCallable;

    /**
     * @var callable|null
     */
    private $eventFilter;

    /**
     * @var callable|null
     */
    private $onException;

    /**
     * @var DeleteProjectionEventHandler
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
        callable $getIdCallable,
        ?callable $filter = null,
        ?callable $onException = null,
        ?ProjectionRepositoryInterface $repository = null
    ) {
        $this->parent = $parent;
        $this->eventHandlersMap = $eventHandlersMap;
        $this->eventClass = $eventClass;
        $this->getIdCallable = $getIdCallable;
        $this->eventFilter = $filter;
        $this->onException = $onException;
        $this->repository = $repository;

        $this->handler = new DeleteProjectionEventHandler(
            $getIdCallable,
            $this->eventFilter,
            $this->onException
        );

        if ($this->repository) {
            $this->handler->setRepository($this->repository);
        }

        $this->eventHandlersMap->addEventHandler($this->eventClass, $this->handler);
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
