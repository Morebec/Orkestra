<?php

namespace Morebec\Orkestra\Logging;

use Morebec\Orkestra\Messaging\Command\CommandInterface;

/**
 * Class CommandPreprocessor
 * Simple Preprocessor transforming a command to an array to be logged
 */
final class CommandPreprocessor implements CommandPreprocessorInterface
{
    /**
     * @inheritDoc
     */
    public function process(CommandInterface $command): array
    {
        $data = (array)$command;
        return [
            'command_name' => get_class($command),
            'data' => $data
        ];
    }
}