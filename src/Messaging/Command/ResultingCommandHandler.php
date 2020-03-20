<?php

namespace Morebec\Orkestra\Messaging\Command;

/**
 * Represents a special type of command handler that returns a result.
 * Implementing this interface should be done sparingly and only in very specific contexts:
 * E.g.: Resource creation where we need to perform something using the newly created resource.
 * (Note that if there is way judging from the command's input to find the newly created resource,
 * Use a Query Instead. For instance, creating s user with a unique username.)
 * Another example could be a long running command that returns a Job Id for progress tracking.
 * NOTE: This type of command is usually executed in a synchronous nature.
 *
 * @see CommandHandlerInterface for implementation guidance
 */
interface ResultingCommandHandler extends CommandHandlerInterface
{
}
