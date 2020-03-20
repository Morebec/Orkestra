<?php

namespace Morebec\Orkestra\EventSourcing;

class EventStoreTrackingUnit
{
    /** @var string */
    private $id;

    /** @var string|null */
    private $lastReadEventId;

    private function __construct(string $id, ?string $lastReadEvent = null)
    {
        if ($id === '') {
            throw new \InvalidArgumentException('The ID of a tracking unit must not be blank');
        }

        $this->id = $id;
        $this->lastReadEventId = $lastReadEvent;
    }

    /**
     * Creates a new tracking unit with a given id and optionally a last Read event id.
     */
    public static function create(string $id, string $lastReadEventId = null): self
    {
        return new self($id, $lastReadEventId);
    }

    public function changeLastReadEventId(string $lastReadEventId): void
    {
        $this->lastReadEventId = $lastReadEventId;
    }

    public function getLastReadEventId(): ?string
    {
        return $this->lastReadEventId;
    }

    /**
     * Resets this tracking unit's last read event id to null.
     * So it appears to have never read any events.
     */
    public function reset(): void
    {
        $this->lastReadEventId = null;
    }
}
