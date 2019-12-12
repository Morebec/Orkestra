<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module\ModuleConfigurationFile;
use Morebec\ValueObjects\File\Path;

class LayerObjectConfiguration
{
    public const SCHEMA_KEY = 'schema';

    public const SUB_DIRECTORY_KEY = 'subdir';
    /**
     * @var LayerObjectSchemaFile
     */
    private $schemaFile;

    /**
     * @var string
     */
    private $subDirectoryName;

    public static function fromArray(ModuleConfigurationFile $moduleConfigurationFile, array $data)
    {
        $schemaFile = new LayerObjectSchemaFile(new Path(
            $moduleConfigurationFile->getDirectory() . '/' . $data[self::SCHEMA_KEY]
        ));
        $subDirectory = $data[self::SUB_DIRECTORY_KEY];

        return new static($schemaFile, $subDirectory);
    }

    protected function __construct(LayerObjectSchemaFile $schemaFile, string $subDirectory)
    {
        $this->schemaFile = $schemaFile;
        $this->subDirectoryName = $subDirectory;
    }

    /**
     * @return LayerObjectSchemaFile
     */
    public function getSchemaFile(): LayerObjectSchemaFile
    {
        return $this->schemaFile;
    }

    /**
     * @return string
     */
    public function getSubDirectoryName(): string
    {
        return $this->subDirectoryName;
    }
}