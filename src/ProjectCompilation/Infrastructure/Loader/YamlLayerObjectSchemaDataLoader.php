<?php


namespace Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader;

use Morebec\ObjectGenerator\Domain\Validation\ObjectSchemaValidator;
use Morebec\ObjectGenerator\Domain\Exception\FileNotFoundException;
use Morebec\ObjectGenerator\Infrastructure\Loader\YamlDefinitionLoader;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidLayerObjectSchemaException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectSchema;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectSchemaFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader\LayerObjectSchemaDataLoaderInterface;

/**
 * Loads the schema of a layer object from a LayerObjectSchemaFile
 */
class YamlLayerObjectSchemaDataLoader extends YamlDefinitionLoader implements LayerObjectSchemaDataLoaderInterface
{
    /**
     * @inheritDoc
     * @param LayerObjectSchemaFile $file
     * @return LayerObjectSchema
     * @throws InvalidLayerObjectSchemaException
     * @throws FileNotFoundException
     */
    public function loadFromFile(LayerObjectSchemaFile $file): array
    {
        $data = $this->loadDefinitionFile($file);
        if(!is_array($data)) {
            throw new InvalidLayerObjectSchemaException(
                "Invalid Module Configuration: It should start with the name of the module followed by its definition at $file"
            );
        }

        $objectName = array_key_first($data);

        return $data;
    }
}