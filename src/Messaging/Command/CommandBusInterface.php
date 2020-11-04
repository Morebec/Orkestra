<?php

namespace Morebec\Orkestra\Messaging\Command;

/**
 * Interface for command buses.
 * Responsible for dispatching commands to the right Command Handler.
 */
interface CommandBusInterface
{
    /**
     * Dispatches the command across the bus to the right command handler.
     * Can return a result depending on the type of command.
     *
     * @see ResultingCommandHandler for more info about returning something from a command handler
     *
     * @return mixed
     */
    public function dispatch(CommandInterface $command);
}
