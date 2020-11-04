<?php

namespace Morebec\Orkestra\Messaging;

/**
 * Resolves the handlers and their methods to use for a specific message.
 */
class MessageHandlerResolver
{
    /**
     * @var MessageHandlerMap
     */
    private $handlerMap;
    /**
     * @var MessageHandlerProvider
     */
    private $handlerProvider;

    public function __construct(MessageHandlerMap $handlerMap, MessageHandlerProvider $handlerProvider)
    {
        $this->handlerMap = $handlerMap;
        $this->handlerProvider = $handlerProvider;
    }

    /**
     * Returns the resolved handlers for a given message.
     *
     * @return array<ResolvedMessageHandler>
     */
    public function resolveHandlers(string $messageClass): array
    {
        $handlerClassesAndMethods = $this->handlerMap->getHandlers($messageClass);

        $resolvedHandlers = [];

        foreach ($handlerClassesAndMethods as $handlerClassAndMethod) {
            [$handlerClass, $method] = explode('::', $handlerClassAndMethod);

            $handler = $this->handlerProvider->getHandler($handlerClass);

            if (!$handler) {
                throw new \RuntimeException("Cannot resolve handler '$handlerClass' Mapped handler instance not found");
            }

            $resolvedHandlers[] = new ResolvedMessageHandler($messageClass, $handler, $method);
        }

        return $resolvedHandlers;
    }
}
