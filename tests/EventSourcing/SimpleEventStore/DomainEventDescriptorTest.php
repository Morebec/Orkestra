<?php

namespace Tests\Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\SimpleEventStore\DomainEventDescriptor;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventId;
use Morebec\Orkestra\Messaging\Event\DomainEventInterface;
use PHPUnit\Framework\TestCase;

class DomainEventDescriptorTest extends TestCase
{
    public function testGetEventMetadata(): void
    {
        $desc = DomainEventDescriptor::forDomainEvent(
            EventId::fromString('test'),
            $this->getDomainEvent()
        );

        $this->assertNotNull($desc->getEventMetadata());
        $this->assertEquals('test.event', (string) $desc->getEventType());
    }

    private function getDomainEvent(): DomainEventInterface
    {
        return new class() implements DomainEventInterface {
            public static function getTypeName(): string
            {
                return 'test.event';
            }
        };
    }
}
