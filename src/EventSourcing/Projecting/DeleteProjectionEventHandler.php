<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

/**
 * Implementation of a ProjectorEventHandler that updates a Projection instance
 * and removes from a repository.
 */
class DeleteProjectionEventHandler extends ProjectorEventHandler
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
        ?callable $filter = null,
        ?callable $exceptionHandler = null
    ) {
        $noop = static function (ProjectionContextInterface $context) {};
        parent::__construct($noop, $filter, $exceptionHandler);
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
            if (!($this->filter)()) {
                return;
            }
        }

        try {
            $id = ($this->getIdCallable)($context);

            // If not found will throw an exception
            $this->repository->findById($id);

            $this->repository->remove($id);
        } catch (\Throwable $t) {
            if (!$this->exceptionHandler) {
                throw $t;
            }
            ($this->exceptionHandler)($context, $t);
        }
    }
}
