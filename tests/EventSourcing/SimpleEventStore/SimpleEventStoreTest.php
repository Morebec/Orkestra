<?php

namespace Tests\Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\SimpleEventStore\DomainEventDescriptor;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventStreamId;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\SimpleEventStorageReaderInterface;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\SimpleEventStorageWriterInterface;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\SimpleEventStore;
use Morebec\Orkestra\Messaging\Context\DomainContext;
use Morebec\Orkestra\Messaging\Context\DomainContextProvider;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\Event\DomainEventInterface;
use PHPUnit\Framework\TestCase;

class SimpleEventStoreTest extends TestCase
{
    public function testAppendToStream(): void
    {
        $domainContextProvider = $this->getMockBuilder(DomainContextProvider::class)->disableOriginalConstructor()->getMock();
        $domainContextProvider->method('getContext')->willReturn(new DomainContext(
           new class() implements DomainMessageInterface {
               public static function getTypeName(): string
               {
                   return 'message.test_event_store';
               }
           },
            new DomainMessageHeaders([
                DomainMessageHeaders::MESSAGE_ID => 'messageId',
                DomainMessageHeaders::CORRELATION_ID => 'correlationId',
            ])
        ));

        $storageReader = $this->getMockBuilder(SimpleEventStorageReaderInterface::class)->getMock();
        $storageWriter = $this->getMockBuilder(SimpleEventStorageWriterInterface::class)->getMock();

        /** @var DomainContextProviderInterface $domainContextProvider */
        /** @var SimpleEventStorageWriterInterface $storageWriter */
        /** @var SimpleEventStorageReaderInterface $storageReader */
        $store = new SimpleEventStore($domainContextProvider, $storageWriter, $storageReader);

        $storageWriter->expects($this->atLeastOnce())->method('appendToStream');

        // Multiple events should trigger only a single appendToStream Call on the writer.
        $event = $this->getMockBuilder(DomainEventDescriptor::class)->disableOriginalConstructor()->getMock();
        $event->method('getEvent')->willReturn(
            $this->getMockBuilder(DomainEventInterface::class)->getMock()
        );
        $store->appendToStream(EventStreamId::fromString('test-stream'), [
            $event,
            $event,
        ]);
    }
}
