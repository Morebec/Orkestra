<?php


/**
 * Handles the Event Template of the form:
 *
 * ```yaml
 * desc: Description of the event
 * use: [] # Uses
 * params: # Parameters for the constructor of the event
 * message: ... # message of the exception that will be displayed since its double quoted in the resulting code it is pass
 *               variables name like: $myVar
 * ```
 *
 * @param $config
 * @param array $data
 * @return array
 */
function handleTemplate($config, array $data): array {
    $objectName = $config->getSchemaFile()->getFilename();

    $use = array_key_exists('use', $data) ? $data['use'] : [];

    $message = $data['message'];

    $objectData = [
        'desc' => $data['desc'],
        'type' => 'class',
        'final' => true,
        'use' => array_merge($use, [
            'classname' => 'HappyWeb\HappyProjects\Core\ACL\Event\Event'
        ]),
        'extends' => 'Event',

        'methods' => [
            '__construct' => [
                'params' => array_key_exists('params', $data) ? $data['params'] : [],
                'code' => "parent::__construct(\"$message\");"
            ]
        ]

    ];

    return [$objectName => $objectData];
}