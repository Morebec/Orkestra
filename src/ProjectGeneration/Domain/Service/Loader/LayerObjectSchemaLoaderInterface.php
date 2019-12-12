<?php


namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Loader;


use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject\LayerObjectSchema;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject\LayerObjectSchemaFile;

/**
 * Responsible for loading Layer Object Schema Files and returning their corresponding
 * instance of LayerObjectSchema
 */
interface LayerObjectSchemaLoaderInterface
{
    /**
     * Loads a Layer Object's schema file and returns its Schema
     * @param LayerObjectSchemaFile $file
     * @return LayerObjectSchema
     */
    public function load(LayerObjectSchemaFile $file): LayerObjectSchema;
}