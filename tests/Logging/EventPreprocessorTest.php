<?php

namespace Tests\Morebec\Orkestra\Logging;

use Morebec\Orkestra\Logging\CommandPreprocessor;
use Morebec\Orkestra\Logging\EventPreprocessor;
use Morebec\Orkestra\Messaging\Command\CommandInterface;
use Morebec\Orkestra\Messaging\Event\EventInterface;
use PHPUnit\Framework\TestCase;

class EventPreprocessorTest extends TestCase
{
    public function testProcess()
    {
        $event = new ExampleEvent();
        $event->stringField  = 'a string';
        $event->intField = 7809;
        $event->booleanField = true;
        $event->arrayField = [
            'key' => 'value',
            'another_key' => 'another_value'
        ];

        $processor = new EventPreprocessor();
        $data = $processor->process($event);

        $this->assertEquals([
            'event_name' => 'Tests\Morebec\Orkestra\Logging\ExampleEvent',
            'data' => [
                'stringField' => 'a string',
                'intField' => 7809,
                'booleanField' => true,
                'arrayField' => [
                    'key' => 'value',
                    'another_key' => 'another_value'
                ]
            ]
        ], $data);
    }
}

class ExampleEvent implements EventInterface
{
    /** @var string */
    public $stringField;

    /** @var int */
    public $intField;

    /** @var bool */
    public $booleanField;

    /** @var string[] */
    public $arrayField;
}