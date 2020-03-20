<?php

namespace Morebec\Orkestra\Module;

use Morebec\Orkestra\Messaging\Command\CommandInterface;
use Morebec\Orkestra\Messaging\Event\EventInterface;
use Morebec\Orkestra\Messaging\Notification\NotificationInterface;
use Morebec\Orkestra\Messaging\Query\QueryInterface;

/**
 * A Module interface acts as a central communication point within and from outside a module.
 * It allows decoupling other modules from directly accessing services from a module.
 *
 * Implementation should be done on an infrastructure level handling Dependency injection
 * and handling the different communication points (namely queries, commands and events).
 * It is the responsibility of a module implementation to dispatch the right commands, queries, events and notifications
 * to the right handlers through the right transports (async, or sync).
 */
interface ModuleInterface
{
    /**
     * Executes a command in a fire and forget fashion in this module.
     */
    public function executeCommand(CommandInterface $command): void;

    /**
     * Executes a command that returns a result in this module.
     *
     * @return mixed
     */
    public function executingResultingCommand(CommandInterface $command);

    /**
     * Executes a query from this module.
     *
     * @return mixed
     */
    public function executeQuery(QueryInterface $query);

    /**
     * Dispatches an event inside this module to subscribed event handlers.
     */
    public function dispatchEvent(EventInterface $event): void;

    /**
     * Dispatches a notification inside this module to subscribed notification handlers.
     */
    public function dispatchNotification(NotificationInterface $notification): void;
}
