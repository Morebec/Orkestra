<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module;

use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidModuleConfigurationException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectSchemaFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\OCFile;
use Morebec\ValueObjects\File\Path;

abstract class AbstractModuleObjectConfiguration
{
    public const SCHEMA_KEY = 'schema';

    public const SUB_DIRECTORY_KEY = 'subdir';

    public const ESSENCE_KEY = 'essence';

    /**
     * @var ModuleObjectSchemaFile
     */
    protected $schemaFile;

    /**
     * @var string
     */
    protected $subDirectoryName;
    /**
     * @var bool
     */
    protected $essence;

    protected function __construct(
        ModuleObjectSchemaFile $schemaFile,
        string $subDirectory,
        bool $essence = false
    ) {
        $this->schemaFile = $schemaFile;
        $this->subDirectoryName = $subDirectory;
        $this->essence = $essence;
    }

    public static function fromConfigurationFileData(OCFile $configurationFile, array $data)
    {
        if (!array_key_exists(self::SCHEMA_KEY, $data)) {
            throw new InvalidModuleConfigurationException("An Object should have a schema at '$configurationFile'");
        }

        if (!$data[self::SCHEMA_KEY]) {
            throw new InvalidModuleConfigurationException('No Value provided for key ' . self::SCHEMA_KEY . "at '$configurationFile'");
        }

        $schemaFile = new LayerObjectSchemaFile(new Path(
            $configurationFile->getDirectory() . '/' . $data[self::SCHEMA_KEY]
        ));

        $subDirectory = '';
        if (array_key_exists(self::SUB_DIRECTORY_KEY, $data)) {
            $subDirectory = $data[self::SUB_DIRECTORY_KEY];
        }

        $essence = false;
        if (array_key_exists(self::ESSENCE_KEY, $data)) {
            $essence = $data[self::ESSENCE_KEY];
        }

        return new static($schemaFile, $subDirectory, $essence);
    }

    /**
     * @return ModuleObjectSchemaFile
     */
    public function getSchemaFile(): ModuleObjectSchemaFile
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
