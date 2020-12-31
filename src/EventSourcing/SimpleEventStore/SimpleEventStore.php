<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use InvalidArgumentException;
use Morebec\Orkestra\DateTime\ClockInterface;
use Morebec\Orkestra\EventSourcing\EventStore\ConcurrencyException;
use Morebec\Orkestra\EventSourcing\EventStore\EventDescriptorInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreSubscriptionIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreSubscriptionInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamIdInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStreamVersionInterface;
use Morebec\Orkestra\EventSourcing\EventStore\StreamedEventCollectionInterface;
use Morebec\Orkestra\Messaging\Context\DomainContextProviderInterface;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;

/**
 * Very simple implementation of an event store.
 * It relies of EventWriters and EventReaders to respectively write and read
 * from a persistent store.
 */
class SimpleEventStore implements EventStoreInterface
{
    public const GLOBAL_STREAM_ID = '*';

    /**
     * @var SimpleEventStorageWriterInterface
     */
    private $storageWriter;

    /**
     * @var SimpleEventStorageReaderInterface
     */
    private $storageReader;

    /**
     * @var DomainContextProviderInterface
     */
    private $domainContextProvider;
    /**
     * @var ClockInterface
     */
    private $clock;

    public function __construct(
        DomainContextProviderInterface $domainContextProvider,
        SimpleEventStorageWriterInterface $eventWriter,
        SimpleEventStorageReaderInterface $eventReader,
        ClockInterface $clock
    ) {
        $this->storageWriter = $eventWriter;
        $this->storageReader = $eventReader;
        $this->domainContextProvider = $domainContextProvider;
        $this->clock = $clock;
    }

    public static function getGlobalStreamId(): EventStreamIdInterface
    {
        return EventStreamId::fromString(self::GLOBAL_STREAM_ID);
    }

    public function appendToStream(
        EventStreamIdInterface $streamId,
        iterable $eventDescriptors,
        ?EventStreamVersionInterface $expectedStreamVersion = null
    ): void {
        if ($streamId === self::getGlobalStreamId()) {
            throw new \LogicException('Cannot append to the global stream as it is a virtual stream.');
        }

        // Ensure all events are event descriptors
        foreach ($eventDescriptors as $descriptor) {
            if (!($descriptor instanceof EventDescriptorInterface)) {
                $expectedType = EventDescriptorInterface::class;
                $actualType = \is_object($descriptor) ? \get_class($descriptor) : \gettype($descriptor);
                throw new InvalidArgumentException("Invalid argument, expected '$expectedType', got '$actualType'");
            }
        }

        $stream = $this->getStream($streamId);
        if (!$stream) {
            $stream = new EventStream($streamId, EventStreamVersion::initial());
            $this->storageWriter->createStream($stream);
        }

        // Check concurrency
        if ($expectedStreamVersion && !$stream->getVersion()->isEqualTo($expectedStreamVersion)) {
            throw new ConcurrencyException($streamId, $expectedStreamVersion, $stream->getVersion());
        }

        $recordedEvents = [];

        $currentVersion = $stream->getVersion()->toInt();
        /** @var EventDescriptorInterface $descriptor */
        foreach ($eventDescriptors as $descriptor) {
            $currentVersion++;
            // Provide metadata about causation and correlation.
            $metadata = $descriptor->getEventMetadata();

            $context = $this->domainContextProvider->getContext();
            $messageHeaders = $context->getMessageHeaders();
//            foreach ($messageHeaders->toArray() as $key => $value) {
//                $metadata->putValue($key, $value);
//            }

            $metadata->putValue('causationId', $context->getMessageId());
            $metadata->putValue('correlationId', $context->getCorrelationId());
            $metadata->putValue('tenantId', $messageHeaders->get(DomainMessageHeaders::TENANT_ID));
            $recordedAt = $this->clock->now();
            $metadata->putValue('recordedAt', $recordedAt);

            // Append to document
            $recordedEvents[] = RecordedEventDescriptor::fromEventDescriptor(
                $descriptor,
                $streamId,
                EventStreamVersion::fromInt($currentVersion),
                $recordedAt
            );
        }

        // Dump the events.
        $this->storageWriter->appendToStream($streamId, $recordedEvents);
    }

    public function readStreamForward(EventStreamIdInterface $streamId, ?EventIdInterface $eventId = null, int $limit = 0): StreamedEventCollectionInterface
    {
        return $this->storageReader->readStreamForward($streamId, $eventId, $limit);
    }

    public function readStreamBackward(EventStreamIdInterface $streamId, ?EventIdInterface $eventId = null, int $limit = 0): StreamedEventCollectionInterface
    {
        return $this->storageReader->readStreamBackward($streamId, $eventId, $limit);
    }

    public function getStream(EventStreamIdInterface $streamId): ?EventStreamInterface
    {
        return $this->storageReader->getStream($streamId);
    }

    public function getSubscription(EventStoreSubscriptionIdInterface $subscriptionId): ?EventStoreSubscriptionInterface
    {
        return $this->storageReader->getSubscription($subscriptionId);
    }

    public function startSubscription(EventStoreSubscriptionInterface $subscription): void
    {
        $this->storageWriter->startSubscription($subscription);
    }

    public function cancelSubscription(EventStoreSubscriptionIdInterface $subscriptionId): void
    {
        $this->storageWriter->cancelSubscription($subscriptionId);
    }

    public function resetSubscription(EventStoreSubscriptionIdInterface $subscriptionId): void
    {
        $this->storageWriter->resetSubscription($subscriptionId);
    }

    public function advanceSubscription(EventStoreSubscriptionIdInterface $subscriptionId, EventIdInterface $eventId): void
    {
        $this->storageWriter->advanceSubscription($subscriptionId, $eventId);
    }
}
