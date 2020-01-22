<?php

namespace Tests\Morebec\Orkestra\Logging;

use Morebec\Orkestra\Logging\CommandPreprocessor;
use Morebec\Orkestra\Messaging\Command\CommandInterface;
use PHPUnit\Framework\TestCase;

class CommandPreprocessorTest extends TestCase
{

    public function testProcess()
    {
        $command = new ExampleCommand();
        $command->stringField  = 'a string';
        $command->intField = 7809;
        $command->booleanField = true;
        $command->arrayField = [
            'key' => 'value',
            'another_key' => 'another_value'
        ];

        $processor = new CommandPreprocessor();
        $data = $processor->process($command);

        $this->assertEquals([
            'command_name' => 'Tests\Morebec\Orkestra\Logging\ExampleCommand',
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

class ExampleCommand implements CommandInterface
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