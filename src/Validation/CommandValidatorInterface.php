<?php


namespace Morebec\Orkestra\Validation;

use Morebec\Orkestra\Messaging\Command\CommandInterface;

/**
 * Interface CommandValidatorInterface
 * Validator interface for validating commands in command handlers
 */
interface CommandValidatorInterface
{
    /**
     * Validates a command.
     * Throws an exception if the command is invalid
     * @throws InvalidCommandException
     * @param CommandInterface $command
     */
    public static function validate(CommandInterface $command): void;
}
