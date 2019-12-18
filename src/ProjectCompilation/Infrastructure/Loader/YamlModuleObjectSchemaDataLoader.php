<?php


namespace Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader;

use Morebec\ObjectGenerator\Domain\Exception\FileNotFoundException;
use Morebec\ObjectGenerator\Infrastructure\Loader\YamlDefinitionLoader;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidModuleObjectSchemaException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\ModuleObjectSchema;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleObjectSchemaFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader\ModuleObjectSchemaDataLoaderInterface;

/**
 * Loads the schema of a layer object from a LayerObjectSchemaFile
 */
class YamlModuleObjectSchemaDataLoader extends YamlDefinitionLoader implements ModuleObjectSchemaDataLoaderInterface
{
    /**
     * @inheritDoc
     * @param ModuleObjectSchemaFile $file
     * @return ModuleObjectSchema
     * @throws InvalidModuleObjectSchemaException
     * @throws FileNotFoundException
     */
    public function loadFile(ModuleObjectSchemaFile $file): array
    {
        $data = $this->loadDefinitionFile($file);
        if (!is_array($data)) {
            throw new InvalidModuleObjectSchemaException(
                "Invalid Module Configuration: It should start with the name of the module followed by its definition at $file"
            );
        }

        $objectName = array_key_first($data);

        return $data;
    }
}
