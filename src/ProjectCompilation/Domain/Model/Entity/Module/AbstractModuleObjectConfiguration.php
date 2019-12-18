<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity;


use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectSchemaFile;

abstract class AbstractModuleObjectConfiguration
{
    public const SCHEMA_KEY = 'schema';

    public const SUB_DIRECTORY_KEY = 'subdir';

    public const ESSENCE_KEY = 'essence';

    /**
     * @var LayerObjectSchemaFile
     */
    private $schemaFile;

    /**
     * @var string
     */
    private $subDirectoryName;
    /**
     * @var bool
     */
    private $essence;

}