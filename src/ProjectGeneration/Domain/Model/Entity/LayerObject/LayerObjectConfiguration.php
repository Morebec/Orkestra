<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject;

use Morebec\Orkestra\ProjectGeneration\Domain\Exception\InvalidModuleConfigurationException;
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

    /**
     * Constructs a Layer Object Configuration from a pre validated array
     * @param ModuleConfigurationFile $moduleConfigurationFile
     * @param array $data
     * @return static
     * @throws InvalidModuleConfigurationException
     */
    public static function fromArray(ModuleConfigurationFile $moduleConfigurationFile, array $data)
    {
        if(!array_key_exists(self::SCHEMA_KEY, $data)) {
            throw new InvalidModuleConfigurationException("An Object should have a schema at '$moduleConfigurationFile'");
        }

        if(!$data[self::SCHEMA_KEY]) {
            throw new InvalidModuleConfigurationException('No Value provided for key ' . self::SCHEMA_KEY . "at '$moduleConfigurationFile'");
        }

        $schemaFile = new LayerObjectSchemaFile(new Path(
            $moduleConfigurationFile->getDirectory() . '/' . $data[self::SCHEMA_KEY]
        ));

        $subDirectory = '';
        if(array_key_exists(self::SUB_DIRECTORY_KEY, $data)) {
            $subDirectory = $data[self::SUB_DIRECTORY_KEY];
        }

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