<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader;

use Morebec\ObjectGenerator\Domain\Exception\FileNotFoundException;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidModuleObjectSchemaException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleObjectSchemaFile;

/**
 * Responsible for loading Layer Object Schema Files and returning their corresponding
 * instance of LayerObjectSchema
 */
interface ModuleObjectSchemaDataLoaderInterface
{
    /**
     * Loads a Layer Object's schema file and returns its Schema data as an array
     * @param ModuleObjectSchemaFile $file
     * @return array
     * @throws InvalidModuleObjectSchemaException
     * @throws FileNotFoundException
     */
    public function loadFile(ModuleObjectSchemaFile $file): array;
}
