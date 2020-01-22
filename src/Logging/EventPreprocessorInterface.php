<?php


namespace Morebec\Orkestra\Logging;

use Morebec\Orkestra\Messaging\Event\EventInterface;

/**
 * Interface EventPreprocessorInterface.
 * A event preprocessor processes a event and its internal data before logging it and then transforms it
 * into an array that can easily be logged in the contextual data of a logger.
 * It is the right place to do some editing of event data before they appear in logs, such as stripping passwords
 * or other sensitive information
 */
interface EventPreprocessorInterface
{
    /**
     * Transforms an event to an array representation editing its properties if necessary
     * @param EventInterface $command
     * @return array
     */
    public function process(EventInterface $command): array;
}
