<?php

namespace Tests\Morebec\Orkestra\EventSourcing;

use Morebec\Orkestra\EventSourcing\EventStore\EventDescriptor;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreTracker;
use Morebec\Orkestra\EventSourcing\EventStoreChaser;
use Morebec\Orkestra\Messaging\Event\EventBusInterface;
use PHPUnit\Framework\TestCase;

class EventStoreChaserTest extends TestCase
{
    public function testProcess(): void
    {
        $eventStoreTracker = $this->getMockBuilder(EventStoreTracker::class)->disableOriginalConstructor()->getMock();
        $eventBus = $this->getMockBuilder(EventBusInterface::class)->getMock();

        /** @var EventStoreTracker $eventStoreTracker */
        /** @var EventBusInterface $eventBus */
        $chaser = new EventStoreChaser($eventStoreTracker, $eventBus);

        // Assert SIDE EFFECT: Messages gets dispatched
        $eventStoreTracker->method('replayFor')->willReturn([
            new EventDescriptor('ID_A', new TestEvent('ID_A')),
            new EventDescriptor('ID_B', new TestEvent('ID_B')),
        ]);

        $eventBus->expects($this->exactly(2))->method('dispatch')->withConsecutive(
            [$this->isInstanceOf(TestEvent::class)],
            [$this->isInstanceOf(TestEvent::class)]
        );

        $chaser->process();
    }
}
