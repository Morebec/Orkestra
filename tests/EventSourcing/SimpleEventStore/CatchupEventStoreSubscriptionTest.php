<?php

namespace Tests\Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\SimpleEventStore\CatchupEventStoreSubscription;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventId;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventStoreSubscriptionId;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventStreamId;
use PHPUnit\Framework\TestCase;

class CatchupEventStoreSubscriptionTest extends TestCase
{
    public function testGetLastEventId()
    {
        $sub = new CatchupEventStoreSubscription(
            EventStoreSubscriptionId::fromString('test'),
            EventStreamId::fromString('test_stream')
        );

        $this->assertNull($sub->getLastEventId());

        $lastEventId = EventId::fromString('test_event');
        $sub = new CatchupEventStoreSubscription(
            EventStoreSubscriptionId::fromString('test'),
            EventStreamId::fromString('test_stream'),
            [],
            $lastEventId
        );

        $this->assertEquals($lastEventId, $sub->getLastEventId());
    }
}
