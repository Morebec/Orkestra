<?php


namespace Morebec\Orkestra\Messaging\Command;

/**
 * CommandHandlerInterface.
 * A Command handler is responsible for handling a command that was dispatched through the command bus.
 * There should be a one-to-one relationship between a command and a command handler
 * To implement this interface, create an __invoke method taking a specific CommandInterface as a parameter
 * @template T of CommandInterface
 */
interface CommandHandlerInterface
{
}
