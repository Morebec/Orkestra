<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

use Throwable;

/**
 * Represents a handling unit within a projector.
 */
class ProjectorEventHandler
{
    /** @var callable represents a predicate used to filter events before they are handled. */
    protected $filter;

    /** @var callable represents the action of handling */
    protected $callable;

    /** @var callable used to handle exceptions specifically. */
    protected $exceptionHandler;

    public function __construct(callable $callable, ?callable $filter = null, ?callable $exceptionHandler = null)
    {
        $this->filter = $filter;
        $this->callable = $callable;
        $this->exceptionHandler = $exceptionHandler;
    }

    /**
     * @throws Throwable
     */
    public function handle(ProjectionContextInterface $context): void
    {
        if ($this->filter) {
            if (!($this->filter)($context)) {
                return;
            }
        }

        try {
            ($this->callable)($context);
        } catch (Throwable $t) {
            if (!$this->exceptionHandler) {
                throw $t;
            }
            ($this->exceptionHandler)($context, $t);
        }
    }
}
