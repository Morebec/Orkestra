<?php

namespace Tests\Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventMetadata;
use PHPUnit\Framework\TestCase;

class EventMetadataTest extends TestCase
{
    public function testPutValue(): void
    {
        $meta = new EventMetadata();
        $meta->putValue('key', 'value');

        $this->assertTrue($meta->hasKey('key'));
    }

    public function testHasKey(): void
    {
        $meta = new EventMetadata();
        $this->assertFalse($meta->hasKey('key'));
        $meta->putValue('key', 'value');

        $this->assertTrue($meta->hasKey('key'));
    }

    public function testRemoveValue(): void
    {
        $meta = new EventMetadata();
        $meta->putValue('key', 'value');
        $meta->removeKey('key');

        $this->assertFalse($meta->hasKey('key'));

        // Should not throw exception
        $meta->removeKey('key');
    }
}
