<?php

namespace Tests\Morebec\Orkestra\EventSourcing\Projecting;

use Morebec\Orkestra\EventSourcing\Projecting\ProjectionContextInterface;
use Morebec\Orkestra\EventSourcing\Projecting\ProjectorEventHandlersMap;
use Morebec\Orkestra\EventSourcing\Projecting\ProjectorEventHandlersMapBuilder;
use PHPUnit\Framework\TestCase;

class ProjectorEventHandlersMapBuilderTest extends TestCase
{
    public function test__construct()
    {
        $mapBuilder = new ProjectorEventHandlersMapBuilder();

        $mapBuilder->where(static function (ProjectionContextInterface $context) {
        });

        $mapBuilder
            ->map(static::class)
            ->when(static function (ProjectionContextInterface $context) {
                $event = $context->getEvent();

                return $event !== null;
            })
            ->as(static function (ProjectionContextInterface $context) {
                $event = $context->getEvent();
            })
        ;

        $this->assertInstanceOf(ProjectorEventHandlersMap::class, $mapBuilder->build());

        $mapBuilder
            ->map(static::class)
            ->createAs(static function (ProjectionContextInterface $context) {
            })
            ->withId(static function (ProjectionContextInterface $context) {
            })
        ;

        $mapBuilder
            ->map(static::class)
            ->deleteWhereId(static function (ProjectionContextInterface $context) {
                return 'ok';
            });

        $this->assertInstanceOf(ProjectorEventHandlersMap::class, $mapBuilder->build());
    }
}
