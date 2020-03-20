<?php

namespace Morebec\Orkestra\Messaging;

use Morebec\Collections\HashMap;

/**
 * The message handler provider is responsible for containing all message handler instances.
 * When a handler is needed it can be fetched from here.
 */
class MessageHandlerProvider
{
    /**
     * @var HashMap
     */
    private $handlers;

    public function __construct()
    {
        $this->handlers = new HashMap();
    }

    /**
     * Adds a handler to this provider.
     */
    public function addHandler(object $handler): void
    {
        $this->handlers->put(\get_class($handler), $handler);
    }

    /**
     * Returns a handler by its class name or null if not found.
     */
    public function getHandler(string $handlerClass): ?object
    {
        if ($this->handlers->containsKey($handlerClass)) {
            return $this->handlers->get($handlerClass);
        }

        return null;
    }
}
