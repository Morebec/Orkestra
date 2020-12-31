<?php

namespace Tests\Morebec\Orkestra\EventSourcing\Projecting;

use Morebec\Orkestra\DateTime\DateTime;
use Morebec\Orkestra\EventSourcing\Projecting\AbstractProjector;
use Morebec\Orkestra\EventSourcing\Projecting\ArrayProjectionContextMetadata;
use Morebec\Orkestra\EventSourcing\Projecting\ProjectionContext;
use Morebec\Orkestra\EventSourcing\Projecting\ProjectionContextInterface;
use Morebec\Orkestra\EventSourcing\Projecting\ProjectionInterface;
use Morebec\Orkestra\EventSourcing\Projecting\ProjectionRepositoryInterface;
use Morebec\Orkestra\EventSourcing\Projecting\ProjectorEventHandlersMap;
use Morebec\Orkestra\EventSourcing\Projecting\ProjectorEventHandlersMapBuilder;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\DomainEventDescriptor;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventId;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventStreamId;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\EventStreamVersion;
use Morebec\Orkestra\EventSourcing\SimpleEventStore\RecordedEventDescriptor;
use Morebec\Orkestra\Messaging\Event\DomainEventInterface;
use PHPUnit\Framework\TestCase;

class AbstractProjectorTest extends TestCase
{
    public function testHandle()
    {
        $p = $this->createProjector();

        $p->project(new ProjectionContext(
            RecordedEventDescriptor::fromEventDescriptor(
                DomainEventDescriptor::forDomainEvent(EventId::fromString('event_id'), $this->createEvent()),
                EventStreamId::fromString('stream'),
                EventStreamVersion::fromInt(2),
                DateTime::now()
            ),
            new ArrayProjectionContextMetadata()
        ));

        $this->assertInstanceOf(AbstractProjector::class, $p);
    }

    private function createProjector(): AbstractProjector
    {
        return new class() extends AbstractProjector {
            public function configureMap(ProjectorEventHandlersMapBuilder $map): ProjectorEventHandlersMap
            {
                $map->usingRepository(new class() implements ProjectionRepositoryInterface {
                    public function add(string $id, ProjectionInterface $p): void
                    {
                    }

                    public function update(string $id, ProjectionInterface $p): void
                    {
                    }

                    public function remove(string $id): void
                    {
                    }

                    public function clear(): void
                    {
                    }

                    public function findById(string $id): ProjectionInterface
                    {
                        return new class() implements ProjectionInterface {
                        };
                    }
                });

                $map
                    ->map(static::class)
                    // ->as([self::class, 'onEvent']);
                    ->createAs(static function (ProjectionContextInterface $context) {
                        return new class() implements ProjectionInterface {
                        };
                    })
                    ->withId(static function (ProjectionContextInterface $context) {
                        return $context->getEvent()::getTypeName();
                    })
                ;

                $map
                    ->map(static::class)
                    ->updateAs(static function () {
                    })->whereId(static function (ProjectionContextInterface $context) {
                        return $context->getEvent()::getTypeName();
                    });

                $map
                    ->map(static::class)
                    ->deleteWhereId(static function (ProjectionContextInterface $context) {
                        return $context->getEvent()::getTypeName();
                    });
                /* ->withId(static function (ProjectionContextInterface $context) {
                    return 'this_is_an_id';
                });
                */

                return $map->build();
            }

            public function onEvent(ProjectionContextInterface $context): void
            {
                dump($context);
            }

            public function reset(): void
            {
                // TODO: Implement reset() method.
            }

            public static function getTypeName(): string
            {
                // TODO: Implement getTypeName() method.
            }
        };
    }

    private function createEvent(): DomainEventInterface
    {
        return new class() implements DomainEventInterface {
            public static function getTypeName(): string
            {
                return 'test_event';
            }
        };
    }
}
