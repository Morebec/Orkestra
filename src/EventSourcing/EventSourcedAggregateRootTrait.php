<?php

namespace Morebec\Orkestra\EventSourcing;

use Morebec\Collections\HashMap;
use Morebec\Orkestra\Messaging\Event\EventInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;

/**
 * This trait handles calling apply methods using reflection.
 * By convention for change handling methods to be called they must be named with applyChangeWhen<Name of the event>.
 */
trait EventSourcedAggregateRootTrait
{
    /**
     * List of apply change methods.
     * Where key is the name of the event that can be handled, and the value is an array of methods.
     *
     * @var HashMap<string, array<string>>
     */
    private $changeListeners;

    /**
     * Applies an event change.
     *
     * @throws ReflectionException
     */
    protected function applyChange(EventInterface $event): void
    {
        if ($this->changeListeners === null) {
            $this->registerEventListeners();
        }

        $eventClass = \get_class($event);
        $methods = $this->changeListeners->get($eventClass);
        if (!$methods) {
            throw new RuntimeException("Missing handler for event $eventClass in Aggregate Root ".\get_class($this));
        }
        foreach ($methods as $method) {
            $this->{$method}($event);
        }
    }

    /**
     * @param array $methods
     *
     * @throws ReflectionException
     */
    private function registerEventListeners(): void
    {
        if ($this->changeListeners !== null) {
            return;
        }

        $r = new ReflectionClass($this);
        $methods = $r->getMethods(ReflectionMethod::IS_PRIVATE);
        foreach ($methods as $method) {
            // Convention: State change methods must start with ApplyChangeWhen
            if (strpos($method->name, 'applyChangeWhen') !== 0) {
                continue;
            }

            // They must have at least one required parameter of type EventInterface
            if ($method->getNumberOfRequiredParameters() !== 1) {
                continue;
            }

            $parameterType = $method->getParameters()[0]->getType();
            if (!is_a((string) $parameterType, EventInterface::class, true)) {
                continue;
            }

            // Add method to list
            $methods = $this->changeListeners->getOrDefault($parameterType, []);
            $methods[] = $method->name;
            $this->changeListeners->put($parameterType, $methods);
        }
    }
}
