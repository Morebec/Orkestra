<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\EventStore\EventStreamIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamVersionInterface;

/**
 * Default implementation of an Event stream.
 */
class EventStream implements EventStreamInterface
{
    /**
     * @var EventStreamIdInterface
     */
    private $id;
    /**
     * @var EventStreamVersionInterface
     */
    private $version;

    public function __construct(EventStreamIdInterface $streamId, EventStreamVersionInterface $streamVersion)
    {
        $this->id = $streamId;
        $this->version = $streamVersion;
    }

    public function getId(): EventStreamIdInterface
    {
        return $this->id;
    }

    public function getVersion(): EventStreamVersionInterface
    {
        return $this->version;
    }
}
