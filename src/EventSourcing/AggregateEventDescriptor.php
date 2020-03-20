<?php

namespace Morebec\Orkestra\EventSourcing;

use Morebec\DateTime\DateTime;
use Morebec\Orkestra\Messaging\Event\EventInterface;
use Morebec\Orkestra\Modeling\AggregateRootIdentifierInterface;

/**
 * An Event descriptor contains metadata about an event in order to describe it in the event store.
 * It can be seen as an envelope.
 */
class AggregateEventDescriptor
{
    /** @var AggregateRootIdentifierInterface Aggregate root identifier */
    public $aggregateId;

    /** @var int aggregate root version */
    public $aggregateVersion;

    /** @var EventInterface event */
    public $payload;

    /** @var DateTime */
    public $occurredAt;

    /** @var string */
    public $eventType;

    /** @var string id to uniquely represent the payload */
    public $eventId;

    public function __construct(
        EventInterface $event,
        AggregateRootIdentifierInterface $aggregateId,
        int $aggregateVersion,
        DateTime $occurredAt
    ) {
        $this->aggregateId = $aggregateId;
        $this->aggregateVersion = $aggregateVersion;
        $this->occurredAt = $occurredAt;
        $this->eventType = (new \ReflectionClass($event))->getShortName();
        $this->eventId = "evt_{$this->aggregateId}:{$this->aggregateVersion}";
    }
}
