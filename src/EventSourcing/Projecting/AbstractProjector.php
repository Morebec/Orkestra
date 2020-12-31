<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

use Throwable;

/**
 * Projector.
 */
abstract class AbstractProjector implements ProjectorInterface
{
    /** @var ProjectorEventHandlersMap */
    protected $handlersMap;

    /** @var callable|null filtering predicate before passing the event to the handlers. */
    protected $predicate;

    /**
     * Handles exceptions of {@link ProjectorEventHandler}.
     * This callable should return a boolean indicating whether or not it should retry the operation.
     *
     * @var callable|null
     */
    protected $exceptionHandler;

    /**
     * @var AbstractProjector[]
     */
    protected $children;

    public function __construct(
        ProjectorEventHandlersMap $handlersMap = null,
        ?callable $predicate = null,
        callable $exceptionHandler = null,
        array $children = []
    ) {
        $this->handlersMap = $handlersMap;
        if (!$this->handlersMap) {
            $this->handlersMap = $this->configureMap(new ProjectorEventHandlersMapBuilder());
        }

        $this->predicate = $predicate;

        $this->exceptionHandler = $exceptionHandler;
        if (!$this->exceptionHandler) {
            $this->exceptionHandler = new RetryTransientExceptionsProjectorExceptionHandler();
        }

        $this->children = [];
        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    public function boot(): void
    {
    }

    public function shutdown(): void
    {
    }

    /**
     * Allows subclasses to configure a Map for this projector using the builder.
     */
    abstract public function configureMap(ProjectorEventHandlersMapBuilder $map): ProjectorEventHandlersMap;

    public function project(ProjectionContextInterface $context): void
    {
        if ($this->predicate) {
            if (!($this->predicate)()) {
                return;
            }
        }

        // Call children first
        foreach ($this->children as $child) {
            $child->project($context);
        }

        $eventClassName = \get_class($context->getEvent());
        $handlers = $this->handlersMap->getEventHandlersForEvent($eventClassName);
        foreach ($handlers as $handler) {
            $this->executeHandler($context, $handler);
        }
    }

    /**
     * Adds a child projector to this projector.
     *
     * @param AbstractProjector $child
     */
    protected function addChild(self $child): void
    {
        $this->children[] = $child;
    }

    /**
     * @throws Throwable
     */
    protected function executeHandler(ProjectionContextInterface $context, ProjectorEventHandler $handler): void
    {
        for ($nbAttempts = 1;; $nbAttempts++) {
            try {
                $handler->handle($context);
                break;
            } catch (Throwable $t) {
                if ($this->exceptionHandler && ($this->exceptionHandler)($context, $t, $nbAttempts)) {
                    continue;
                }

                throw $t;
            }
        }
    }
}
