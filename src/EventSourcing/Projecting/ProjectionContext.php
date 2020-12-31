<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

use Morebec\Orkestra\EventSourcing\EventStore\EventDescriptorInterface;
use Morebec\Orkestra\EventSourcing\EventStore\RecordedEventDescriptorInterface;
use Morebec\Orkestra\Messaging\Event\DomainEventInterface;

class ProjectionContext implements ProjectionContextInterface
{
    /**
     * @var EventDescriptorInterface
     */
    private $eventDescriptor;
    /**
     * @var ProjectionContextMetadataInterface
     */
    private $metadata;

    public function __construct(
        RecordedEventDescriptorInterface $eventDescriptor,
        ProjectionContextMetadataInterface $metadata
    ) {
        $this->eventDescriptor = $eventDescriptor;
        $this->metadata = $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function getEvent(): DomainEventInterface
    {
        return $this->eventDescriptor->getEvent();
    }

    /**
     * {@inheritdoc}
     */
    public function getEventDescriptor(): RecordedEventDescriptorInterface
    {
        return $this->eventDescriptor;
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata(): ProjectionContextMetadataInterface
    {
        return $this->metadata;
    }
}
