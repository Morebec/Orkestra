<?php

namespace Tests\Morebec\Orkestra\EventSourcing;

use Morebec\Orkestra\EventSourcing\EventStore\EventDescriptor;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreTrackingUnitRepositoryInterface;
use Morebec\Orkestra\EventSourcing\EventStore\EventStoreTracker;
use Morebec\Orkestra\Messaging\Event\EventInterface;
use PHPUnit\Framework\TestCase;

class EventStoreTrackerTest extends TestCase
{
    public function testResetFor(): void
    {
        $eventStore = $this->getMockBuilder(EventStoreInterface::class)->getMock();

        $eventStore->method('readAllFromTimestampForward')->willReturn([
            new EventDescriptor('ID_A', new TestEvent('ID_A')),
            new EventDescriptor('ID_B', new TestEvent('ID_B')),
        ]);

        $trackingUnitRepository = $this->getMockBuilder(EventStoreTrackingUnitRepositoryInterface::class)->getMock();
        /** @var EventStoreInterface $eventStore */
        /** @var EventStoreTrackingUnitRepositoryInterface $trackingUnitRepository */
        $tracker = new EventStoreTracker($eventStore, $trackingUnitRepository);

        $iterator = $tracker->replayFor('test');
        foreach ($iterator as $event) {
            break;
        }

        $tracker->resetFor('test');
        $this->assertCount(2, $tracker->replayFor('test'));
    }

    public function testReplayFor(): void
    {
        $eventStore = $this->getMockBuilder(EventStoreInterface::class)->getMock();

        $eventStore->method('readAllFromTimestampForward')->willReturn([
            new EventDescriptor('ID_A', new TestEvent('ID_A')),
            new EventDescriptor('ID_B', new TestEvent('ID_B')),
        ]);

        $trackingUnitRepository = $this->getMockBuilder(EventStoreTrackingUnitRepositoryInterface::class)->getMock();
        /** @var EventStoreInterface $eventStore */
        /** @var EventStoreTrackingUnitRepositoryInterface $trackingUnitRepository */
        $tracker = new EventStoreTracker($eventStore, $trackingUnitRepository);

        $count = 0;
        foreach ($tracker->replayFor('test') as $event) {
            $count++;
        }

        $this->assertEquals(2, $count);
    }
}

class TestEvent implements EventInterface
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
