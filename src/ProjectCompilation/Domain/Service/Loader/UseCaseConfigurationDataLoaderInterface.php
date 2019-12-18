<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader;

use Morebec\ObjectGenerator\Domain\Exception\FileNotFoundException;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidModuleObjectSchemaException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectSchemaFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\UseCase\UseCaseConfigurationFile;

/**
 * Interface for loading use case configuration files
 */
interface UseCaseConfigurationDataLoaderInterface
{
    /**
     * Loads a Use Case configuration file and returns its data as an array
     * @param UseCaseConfigurationFile $file
     * @return array
     */
    public function loadDataFromFile(UseCaseConfigurationFile $file): array;
}
