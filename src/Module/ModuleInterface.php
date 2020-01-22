<?php


namespace Morebec\Orkestra\Module;

use Morebec\Orkestra\Messaging\Command\CommandInterface;
use Morebec\Orkestra\Messaging\Event\EventInterface;
use Morebec\Orkestra\Messaging\Query\QueryInterface;
use Morebec\Orkestra\Notification\NotificationInterface;

/**
 * A Module interface acts as a central communication point within and from outside a module.
 * It allows decoupling other modules from directly accessing services from a module.
 *
 * Implementation should be done on an infrastructure level handling Dependency injection
 * and handling the different communication points (namely queries, commands and events).
 * It is the responsibility of a module implementation to dispatch the right commands, queries, events and notifications
 * to the right handlers through the right transports (async, or sync)
 */
interface ModuleInterface
{
    /**
     * Executes a command in a fire and forget fashion in this module
     * @param CommandInterface $command
     */
    public function executeCommand(CommandInterface $command): void;

    /**
     * Executes a command that returns a result in this module
     * @param CommandInterface $command
     * @return mixed
     */
    public function executingResultingCommand(CommandInterface $command);

    /**
     * Executes a query from this module
     * @param QueryInterface $query
     * @return mixed
     */
    public function executeQuery(QueryInterface $query);

    /**
     * Dispatches an event inside this module.
     * @param EventInterface $event
     */
    public function dispatchEvent(EventInterface $event): void;

    /**
     * Dispatches a notification inside this module
     * @param NotificationInterface $notification
     */
    public function dispatchNotification(NotificationInterface $notification): void;
}
