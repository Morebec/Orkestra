<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

/**
 * Special implementation of a ProjectorEventHandler that creates a Projection instance
 * and adds it to a repository.
 */
class CreateProjectionEventHandler extends ProjectorEventHandler
{
    /**
     * @var callable
     */
    private $getIdCallable;
    /**
     * @var ProjectionRepositoryInterface
     */
    private $repository;

    public function __construct(
        callable $getIdCallable,
        callable $callable,
        ?callable $filter = null,
        ?callable $exceptionHandler = null
    ) {
        parent::__construct($callable, $filter, $exceptionHandler);
        $this->getIdCallable = $getIdCallable;
        $this->repository = null;
    }

    /**
     * Sets the repository to be used by this handler.
     */
    public function setRepository(ProjectionRepositoryInterface $repository): void
    {
        $this->repository = $repository;
    }

    public function handle(ProjectionContextInterface $context): void
    {
        if (!$this->repository) {
            throw new ProjectionException('A CRUD event handler must have a repository');
        }

        if ($this->filter) {
            if (!($this->filter)($context)) {
                return;
            }
        }

        try {
            $id = ($this->getIdCallable)($context);
            $data = ($this->callable)($context);

            $this->repository->add($id, $data);
        } catch (\Throwable $t) {
            if (!$this->exceptionHandler) {
                throw $t;
            }
            ($this->exceptionHandler)($context, $t);
        }
    }
}
