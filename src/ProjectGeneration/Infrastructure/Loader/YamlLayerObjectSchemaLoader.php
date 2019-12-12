<?php


namespace Morebec\Orkestra\ProjectGeneration\Infrastructure\Loader;


use Morebec\ObjectGenerator\Domain\Exception\FileNotFoundException;
use Morebec\ObjectGenerator\Infrastructure\Loader\YamlDefinitionLoader;
use Morebec\Orkestra\ProjectGeneration\Domain\Exception\InvalidLayerObjectSchemaException;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject\LayerObjectSchema;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject\LayerObjectSchemaFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Loader\LayerObjectSchemaLoaderInterface;

/**
 * Loads the schema of a layer object from a LayerObjectSchemaFile
 */
class YamlLayerObjectSchemaLoader extends YamlDefinitionLoader implements LayerObjectSchemaLoaderInterface
{
    /**
     * @inheritDoc
     * @param LayerObjectSchemaFile $file
     * @return LayerObjectSchema
     * @throws InvalidLayerObjectSchemaException
     * @throws FileNotFoundException
     */
    public function load(LayerObjectSchemaFile $file): LayerObjectSchema
    {
        $data = $this->loadDefinitionFile($file);
        if(!is_array($data)) {
            throw new InvalidLayerObjectSchemaException(
                "Invalid Module Configuration: It should start with the name of the module followed by its definition at $file"
            );
        }

        $objectName = array_key_first($data);
        $data = $data[$objectName];


        return LayerObjectSchema::createFromArray($file, $data);
    }
}