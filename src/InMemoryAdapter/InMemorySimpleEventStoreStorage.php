<?php

namespace Morebec\Orkestra\InMemoryAdapter;

use Morebec\Orkestra\EventSourcing\EventStore\EventDescriptorInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreSubscriptionIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreSubscriptionInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamInterface;
use Morebec\Orkestra\EventSourcing\EventStore\StreamedEventCollectionInterface;
use Morebec\Orkestra\EventSourcing\EventStore\StreamNotFoundException;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\CatchupEventStoreSubscription;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventStream;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventStreamVersion;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\SimpleEventStorageReaderInterface;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\SimpleEventStorageWriterInterface;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\StreamedEventCollection;
use Morebec\Orkestra\Modeling\DomainEventCollection;
use Morebec\Orkestra\Modeling\DomainEventCollectionInterface;
use Morebec\Orkestra\Modeling\TypedCollection;

class InMemorySimpleEventStoreStorage implements SimpleEventStorageReaderInterface, SimpleEventStorageWriterInterface
{
    /**
     * @var array
     */
    private $streams;

    /**
     * @var DomainEventCollectionInterface
     */
    private $events;

    /** @var TypedCollection */
    private $subscriptions;

    public function __construct()
    {
        $this->streams = [];
        $this->events = new DomainEventCollection();
        $this->subscriptions = new TypedCollection(EventStoreSubscriptionInterface::class);
    }

    public function readStreamForward(EventStreamIdInterface $streamId, ?EventIdInterface $eventId = null, int $limit = 0): StreamedEventCollectionInterface
    {
        $stream = $this->getStream($streamId);
        if (!$stream) {
            throw new StreamNotFoundException($streamId);
        }

        return new StreamedEventCollection($streamId, $this->streams[(string) $streamId]);
    }

    public function readStreamBackward(EventStreamIdInterface $streamId, ?EventIdInterface $eventId = null, int $limit = 0): StreamedEventCollectionInterface
    {
        $stream = $this->getStream($streamId);
        if (!$stream) {
            throw new StreamNotFoundException($streamId);
        }

        return new StreamedEventCollection($streamId, array_reverse($this->streams[(string) $streamId]));
    }

    public function getStream(EventStreamIdInterface $streamId): ?EventStreamInterface
    {
        $streamIdKey = (string) $streamId;

        if (!\array_key_exists($streamIdKey, $this->streams)) {
            return null;
        }

        return new EventStream($streamId, EventStreamVersion::fromInt(\count($this->streams[$streamIdKey])));
    }

    public function getSubscription(EventStoreSubscriptionIdInterface $subscriptionId): ?EventStoreSubscriptionInterface
    {
        return $this->subscriptions->findFirstOrDefault(static function (EventStoreSubscriptionInterface $s) use ($subscriptionId) {
            return $s->getId()->isEqualTo($subscriptionId);
        });
    }

    public function createStream(EventStream $stream): void
    {
        $streamId = (string) $stream->getId();

        if (!\array_key_exists($streamId, $this->streams)) {
            $this->streams[$streamId] = [];
        }
    }

    public function appendToStream(EventStreamIdInterface $streamId, iterable $recordedEvents): void
    {
        $streamIdKey = (string) $streamId;

        if (!\array_key_exists($streamIdKey, $this->streams)) {
            $this->streams[$streamIdKey] = [];
        }

        foreach ($recordedEvents as $eventDescriptor) {
            $this->streams[$streamIdKey][] = $eventDescriptor;
        }

        /** @var EventDescriptorInterface $eventDescriptor */
        foreach ($recordedEvents as $eventDescriptor) {
            $this->events->add($eventDescriptor->getEvent());
        }
    }

    public function startSubscription(EventStoreSubscriptionInterface $subscription): void
    {
        $this->subscriptions->add($subscription);
    }

    public function cancelSubscription(EventStoreSubscriptionIdInterface $subscriptionId): void
    {
        $this->subscriptions = $this->subscriptions->filter(static function (EventStoreSubscriptionInterface $s) use ($subscriptionId) {
            return !$s->getId()->isEqualTo($subscriptionId);
        });
    }

    public function resetSubscription(EventStoreSubscriptionIdInterface $subscriptionId): void
    {
        /** @var CatchupEventStoreSubscription $sub */
        $sub = $this->subscriptions->findFirstOrDefault(static function (EventStoreSubscriptionInterface $s) use ($subscriptionId) {
            return $s->getId()->isEqualTo($subscriptionId);
        });

        if (!$sub) {
            return;
        }

        $this->cancelSubscription($subscriptionId);
        $this->subscriptions->add(new CatchupEventStoreSubscription($subscriptionId, $sub->getStreamId(), $sub->getTypeFilter()));
    }

    public function advanceSubscription(EventStoreSubscriptionIdInterface $subscriptionId, EventIdInterface $eventId): void
    {
        /** @var CatchupEventStoreSubscription $sub */
        $sub = $this->subscriptions->findFirstOrDefault(static function (EventStoreSubscriptionInterface $s) use ($subscriptionId) {
            return $s->getId()->isEqualTo($subscriptionId);
        });

        if (!$sub) {
            return;
        }

        $this->cancelSubscription($subscriptionId);
        $this->subscriptions->add(new CatchupEventStoreSubscription(
            $subscriptionId,
            $sub->getStreamId(),
            $sub->getTypeFilter(),
            $eventId
        ));
    }
}
