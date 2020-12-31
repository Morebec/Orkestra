<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

interface ProjectionRepositoryInterface
{
    /**
     * Adds a projection to ths repository.
     */
    public function add(string $id, ProjectionInterface $p): void;

    /**
     * Updates a projection in this repository.
     */
    public function update(string $id, ProjectionInterface $p): void;

    /**
     * Removes a projection from this repository.
     */
    public function remove(string $id): void;

    /**
     * Clears this repository from any data.
     */
    public function clear(): void;

    /**
     * @throws ProjectionNotFoundException
     */
    public function findById(string $id): ProjectionInterface;
}
