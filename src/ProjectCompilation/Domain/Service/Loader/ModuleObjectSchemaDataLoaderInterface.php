<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader;


use Morebec\ObjectGenerator\Domain\Exception\FileNotFoundException;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidLayerObjectSchemaException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectSchemaFile;

/**
 * Responsible for loading Layer Object Schema Files and returning their corresponding
 * instance of LayerObjectSchema
 */
interface LayerObjectSchemaDataLoaderInterface
{
    /**
     * Loads a Layer Object's schema file and returns its Schema data as an array
     * @param LayerObjectSchemaFile $file
     * @return array
     * @throws InvalidLayerObjectSchemaException
     * @throws FileNotFoundException
     */
    public function loadFromFile(LayerObjectSchemaFile $file): array;
}