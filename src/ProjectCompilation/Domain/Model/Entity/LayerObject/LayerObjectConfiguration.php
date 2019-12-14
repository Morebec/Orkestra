<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject;

use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidModuleConfigurationException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfigurationFile;
use Morebec\ValueObjects\File\Path;

class LayerObjectConfiguration
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

        $essence = false;
        if(array_key_exists(self::ESSENCE_KEY, $data)) {
            $essence = $data[self::ESSENCE_KEY];
        }

        return new static($schemaFile, $subDirectory, $essence);
    }

    protected function __construct(LayerObjectSchemaFile $schemaFile, string $subDirectory, bool $essence)
    {
        $this->schemaFile = $schemaFile;
        $this->subDirectoryName = $subDirectory;
        $this->essence = $essence;
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

    /**
     * Indicates if this object has an essence using the Object Essence Pattern
     * @return bool
     */
    public function hasEssence(): bool
    {
        return $this->essence;
    }
}