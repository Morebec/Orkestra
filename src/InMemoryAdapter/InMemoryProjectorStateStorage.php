<?php

namespace Morebec\Orkestra\InMemoryAdapter;

use Morebec\Orkestra\EventSourcing\Projecting\ProjectorInterface;
use Morebec\Orkestra\EventSourcing\Projecting\ProjectorStateStorageInterface;
use Morebec\Orkestra\Modeling\TypedCollection;

class InMemoryProjectorStateStorage implements ProjectorStateStorageInterface
{
    /** @var TypedCollection */
    private $states;

    public function __construct()
    {
        $this->states = new TypedCollection(InMemoryProjectorState::class);
    }

    public function markBroken(ProjectorInterface $projector, string $eventId): void
    {
        $projectorState = $this->findOrInit($projector::getTypeName());
        $projectorState->status = 'BROKEN';
    }

    public function markBooting(ProjectorInterface $projector): void
    {
        $projectorState = $this->findOrInit($projector::getTypeName());
        $projectorState->status = 'BOOTING';
    }

    public function markBooted(ProjectorInterface $projector): void
    {
        $projectorState = $this->findOrInit($projector::getTypeName());
        $projectorState->status = 'BOOTED';
    }

    public function markRunning(ProjectorInterface $projector): void
    {
        $projectorState = $this->findOrInit($projector::getTypeName());
        $projectorState->status = 'RUNNING';
    }

    public function markShutdown(ProjectorInterface $projector): void
    {
        $projectorState = $this->findOrInit($projector::getTypeName());
        $projectorState->status = 'SHUTDOWN';
    }

    public function isBroken(ProjectorInterface $projector): bool
    {
        $projectorState = $this->findOrInit($projector::getTypeName());

        return $projectorState->status === 'BROKEN';
    }

    public function isRunning(ProjectorInterface $projector): bool
    {
        $projectorState = $this->findOrInit($projector::getTypeName());

        return $projectorState->status === 'RUNNING';
    }

    private function findOrInit(string $projectorTypeName): InMemoryProjectorState
    {
        $projectorState = $this->states->findFirstOrDefault(static function ($projectorState) use ($projectorTypeName) {
            return $projectorState['typeName'] === $projectorTypeName;
        });

        if (!$projectorState) {
            $projectorState = new InMemoryProjectorState($projectorTypeName, 'NEW', null);
            $this->states->add($projectorState);
        }

        return $projectorState;
    }
}
