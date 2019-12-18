<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject;

use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidModuleConfigurationException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\AbstractModuleObjectConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfigurationFile;

class LayerObjectConfiguration extends AbstractModuleObjectConfiguration
{
    /**
     * Constructs a Layer Object Configuration from a pre validated array
     * @param ModuleConfigurationFile $moduleConfigurationFile
     * @param array $data
     * @return static
     * @throws InvalidModuleConfigurationException
     */
    public static function fromArray(ModuleConfigurationFile $moduleConfigurationFile, array $data): self
    {
        return self::fromConfigurationFileData($moduleConfigurationFile, $data);
    }

    protected function __construct(
        LayerObjectSchemaFile $schemaFile,
        string $subDirectory,
        bool $essence = false
    ) {
        parent::__construct($schemaFile, $subDirectory, $essence);
    }
}
