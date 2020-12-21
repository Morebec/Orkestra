<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

use LogicException;
use Morebec\Orkestra\EventSourcing\EventStore\EventDescriptorInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreInterface;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\CatchupEventStoreSubscription;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventStoreSubscriptionId;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\SimpleEventStore;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * This implementation of a Projectionist tracks the Event Store for all the projectors it handles
 * and make sure they are up to date. It cal also take compensatory actions in the case failure.
 */
class EventStoreProjectionist implements ProjectionistInterface
{
    /** @var int Maximum number of retries in the case where a projector fails. */
    public const MAX_NUMBER_RETRIES = 5;

    /**
     * @var EventStoreInterface
     */
    private $eventStore;

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ProjectorStateStorageInterface
     */
    private $projectorStateStorage;

    public function __construct(
        EventStoreInterface $eventStore,
        ProjectorStateStorageInterface $projectorStateStorage,
        LoggerInterface $logger
    ) {
        $this->eventStore = $eventStore;
        $this->logger = $logger;
        $this->projectorStateStorage = $projectorStateStorage;
    }

    /**
     * Runs a given projector by first booting it up, running it and then shutting shutting it down.
     */
    public function runProjector(ProjectorInterface $projector): void
    {
        // Do not run broken projectors
        if ($this->projectorStateStorage->isBroken($projector)) {
            return;
        }

        // Do not run already running projectors
        if ($this->projectorStateStorage->isRunning($projector)) {
            return;
        }

        $this->bootProjector($projector);

        $projectorTypeName = $projector->getTypeName();
        $subscriptionId = EventStoreSubscriptionId::fromString($projectorTypeName);

        /** @var CatchupEventStoreSubscription $subscription */
        $subscription = $this->eventStore->getSubscription($subscriptionId);

        if (!$subscription) {
            throw new LogicException(sprintf('No Subscription found for projector %s did you boot the projector before running it?', $projector->getTypeName()));
        }

        $eventStream = $this->eventStore->readStreamForward($subscription->getStreamId(), $subscription->getLastEventId());

        $this->projectorStateStorage->markRunning($projector);

        /* @var EventDescriptorInterface $event */
        foreach ($eventStream as $eventDescriptor) {
            $exception = null;
            for ($i = 0; $i <= self::MAX_NUMBER_RETRIES; $i++) {
                try {
                    $projector->project($eventDescriptor->getEvent());
                    $exception = null;
                    break;
                } catch (Throwable $throwable) {
                    $exception = $throwable;
                }
            }

            if ($exception) {
                $this->logger->error(
                    'Projector "{projectorTypeName}" failed at event "{eventId}: {exceptionMessage}"', [
                        'projectorTypeName' => $projectorTypeName,
                        'eventId' => (string) $eventDescriptor->getEventId(),
                        'exception' => $exception,
                        'exceptionClass' => \get_class($exception),
                        'exceptionMessage' => $exception->getMessage(),
                        'exceptionFile' => $exception->getFile(),
                        'exceptionLine' => $exception->getLine(),
                        'exceptionStackTrace' => $exception->getTraceAsString(),
                    ]
                );
                $this->projectorStateStorage->markBroken($projector, $eventDescriptor->getEventId());

                return;
            }
            $this->eventStore->advanceSubscription($subscriptionId, $eventDescriptor->getEventId());
        }

        $this->shutdownProjector($projector);
    }

    /**
     * Replays a given projector from the start.
     */
    public function replayProjector(ProjectorInterface $projector): void
    {
        $this->resetProjector($projector);
        $this->runProjector($projector);
    }

    /**
     * Boots a given projector.
     */
    public function bootProjector(ProjectorInterface $projector): void
    {
        $this->projectorStateStorage->markBooting($projector);
        // Ensure a subscription exist in the event store for the projector.
        $subscriptionId = EventStoreSubscriptionId::fromString($projector->getTypeName());
        $subscription = $this->eventStore->getSubscription($subscriptionId);
        if (!$subscription) {
            $this->eventStore->startSubscription(new CatchupEventStoreSubscription(
                $subscriptionId,
                SimpleEventStore::getGlobalStreamId()
            ));
        }
        $projector->boot();
        $this->projectorStateStorage->markBooted($projector);
    }

    /**
     * Shuts down a projector after successfully having processed what it needed.
     */
    public function shutdownProjector(ProjectorInterface $projector): void
    {
        $this->projectorStateStorage->markShutdown($projector);
        $projector->shutdown();
    }

    public function resetProjector(ProjectorInterface $projector): void
    {
        $projector->reset();
        $subscriptionId = EventStoreSubscriptionId::fromString($projector->getTypeName());
        $subscription = $this->eventStore->getSubscription($subscriptionId);
        if ($subscription) {
            $this->eventStore->resetSubscription($subscriptionId);
        }
        $this->projectorStateStorage->markShutdown($projector);
    }
}
