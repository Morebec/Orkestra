<?php


namespace Morebec\Orkestra\Logging;

use Morebec\Orkestra\Messaging\Event\EventInterface;

class EventPreprocessor implements EventPreprocessorInterface
{
    /**
     * @inheritDoc
     */
    public function process(EventInterface $command): array
    {
        $data = (array)$command;
        return [
            'event_name' => get_class($command),
            'data' => $data
        ];
    }
}
