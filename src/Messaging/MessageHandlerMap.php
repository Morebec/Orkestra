<?php

namespace Morebec\Orkestra\Messaging;

use Morebec\Collections\HashMap;

/**
 * Represents a map of all the messages with their different callable handlers.
 * All values are provided as class names
 * The map has the following structure
 * [
 *    'Message::class' => [
 *        'MessageHandler::methodName',
 *        'OtherMessageHandle::methodName'
 *     ],
 * ].
 * This can than be used with the Message Handler provider to retrieve the handler instance to call
 * for handling a message.
 */
class MessageHandlerMap
{
    /**
     * @var HashMap
     */
    private $map;

    public function __construct(array $map = [])
    {
        $this->map = new HashMap($map);
    }

    /**
     * Registers a message handler for a given message.
     * Handlers are in the following notation <Message-handler-class-name>::<method-name>.
     */
    public function registerMessageHandler(string $messageClass, string $handlerClassMethod): void
    {
        if (!$this->map->containsKey($messageClass)) {
            $this->map->put($messageClass, []);
        }

        $handlers = $this->map->get($messageClass);

        if (\in_array($handlerClassMethod, $handlers, true)) {
            return;
        }

        $handlers[] = $handlerClassMethod;

        $this->map->put($messageClass, $handlers);
    }

    /**
     * Returns the handlers for a given message class.
     */
    public function getHandlers(string $messageClass): array
    {
        if ($this->map->containsKey($messageClass)) {
            return $this->map->get($messageClass);
        }

        // The class does not exist as is, check for interface and inheritance
        // This would allow a method like method(MessageInterface $message)
        // To be a catch all for all events that implements this Interface
        // Only support this feature for interfaces and not for concrete inheritance as
        // that would cause more trouble than anything.
        // Events should by final by design.
        // (Although message inheritance is rare, it can happen)

        $handlers = [];
        foreach ($this->map as $msgClazz => $handler) {
            $messageImplements = class_implements($messageClass);
            if (\in_array($msgClazz, $messageImplements, true)) {
                $handlers[] = $handler;
            }
        }

        return $handlers;
    }
}
