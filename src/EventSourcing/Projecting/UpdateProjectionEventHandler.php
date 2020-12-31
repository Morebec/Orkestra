<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

/**
 * Implementation of a ProjectorEventHandler that updates a Projection instance
 * and updates it in a repository.
 */
class UpdateProjectionEventHandler extends ProjectorEventHandler
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
    }

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

            $data = $this->repository->findById($id);
            ($this->callable)($context, $data);
            $this->repository->update($id, $data);
        } catch (\Throwable $t) {
            if (!$this->exceptionHandler) {
                throw $t;
            }
            ($this->exceptionHandler)($context, $t);
        }
    }
}
