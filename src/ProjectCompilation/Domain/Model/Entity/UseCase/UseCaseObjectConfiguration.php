<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\UseCase;

use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidModuleConfigurationException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectSchemaFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\AbstractModuleObjectConfiguration;

class UseCaseObjectConfiguration extends AbstractModuleObjectConfiguration
{
    protected function __construct(
        LayerObjectSchemaFile $schemaFile,
        string $subDirectory,
        bool $essence = false
    ) {
        parent::__construct($schemaFile, $subDirectory, $essence);
    }

    /**
     * @param UseCaseConfigurationFile $configurationFile
     * @param array $data
     * @return UseCaseObjectConfiguration
     * @throws InvalidModuleConfigurationException
     */
    public static function fromArray(UseCaseConfigurationFile $configurationFile, array $data): self
    {
        return self::fromConfigurationFileData($configurationFile, $data);
    }
}
