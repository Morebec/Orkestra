<?php


namespace Morebec\Orkestra\Logging;

use Morebec\Orkestra\Messaging\Command\CommandInterface;

/**
 * Interface CommandPreprocessorInterface.
 * A command preprocessor processes a Command and its internal data before logging it and then transforms it
 * into an array that can easily be logged in the contextual data of a logger.
 * It is the right place to do some editing of command data before they appear in logs, such as stripping passwords
 * or other sensitive information
 */
interface CommandPreprocessorInterface
{
    /**
     * Transforms a command to an array representation editing its properties if necessary
     * @param CommandInterface $command
     * @return array
     */
    public function process(CommandInterface $command): array;
}
