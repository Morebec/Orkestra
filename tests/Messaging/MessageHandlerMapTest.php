<?php

namespace Tests\Morebec\Orkestra\Messaging;

use Morebec\Orkestra\Messaging\Event\AbstractEvent;
use Morebec\Orkestra\Messaging\Event\EventInterface;
use Morebec\Orkestra\Messaging\MessageHandlerMap;
use PHPUnit\Framework\TestCase;
use Throwable;

class MessageHandlerMapTest extends TestCase
{
    public function testGetHandlers(): void
    {
        $map = new MessageHandlerMap();
        $map->registerMessageHandler(EventInterface::class, 'MessageHandler::method');

        $this->assertNotEmpty($map->getHandlers(EventInterface::class));
        $this->assertNotEmpty($map->getHandlers(AbstractEvent::class)); // Inheritance (implements interface)

        $map = new MessageHandlerMap();
        $map->registerMessageHandler(AbstractEvent::class, 'MessageHandler::method');
        $this->assertNotEmpty($map->getHandlers(AbstractEvent::class));

        // Inheritance (extends other class), should fail concrete inheritance is NOT supported.
        $this->assertEmpty($map->getHandlers(TestChildEvent::class));
        $this->assertEmpty($map->getHandlers(Throwable::class)); // Completely unrelated
    }
}
