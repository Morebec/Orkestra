<?php


namespace Morebec\Orkestra\ProjectGeneration\Application\Console\ConsoleCommand;

/**
 * Command to generate an Entity OC File
 */
class GenerateEntityConsoleCommand extends AbstractGenerateSchemaConsoleConsoleCommand
{
    protected static $defaultName = 'gen:entity';

    /**
     * @param $objectName
     * @return array
     */
    public function getStub($objectName): array
    {
        return [
            $objectName => [
                'desc' => "Description of {$objectName}",
                'type' => "class",
                'props' => [],
                'methods' => [
                    '__construct' => [
                        'desc' => "{$objectName} constructor",
                        'params' => [
                            "id" => [
                                'desc' => "Id of ${objectName}",
                                'type' => "{$objectName}Id",
                                'init' => true
                            ]
                        ]
                    ],

                    'create' => [
                        'static' => true,
                        'desc' => "Creates an instance of {$objectName}",
                        'params' => [
                            "id" => [
                                'desc' => "Id of ${objectName}",
                                'type' => "{$objectName}Id",
                                'init' => true
                            ]
                        ],
                        'return' => [
                            'type' => 'self'
                        ]

                    ],

                ]
            ]
        ];
    }
}