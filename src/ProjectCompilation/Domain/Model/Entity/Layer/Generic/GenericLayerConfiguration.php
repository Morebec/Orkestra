<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Generic;

use Assert\Assert;
use Assert\Assertion;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfigurationFile;

class GenericLayerConfiguration extends AbstractLayerConfiguration
{
    public static function fromArray(string $layerName, ModuleConfigurationFile $moduleConfigurationFile, array $data): self
    {
        // Every key in the layer configuration corresponds to a direct subdirectory

        $subDirNames = [];
        $layerObjects = [];

        foreach ($data as $subDirName => $v) {

            // Add support for Layer without sub directories
            if (!is_string($subDirName)) {
                $subDirName = '';
                $subDirNames[] = '';
                $layerObjects[$subDirName][] = $v;
                continue;
            }

            $subDirNames[] = $subDirName;
            foreach ($v as $object) {
                $layerObjects[$subDirName][] = $object;
            }
        }

        // Make subDirNames Unique
        $subDirNames = array_keys(array_flip($subDirNames));

        $description = null;
        if (array_key_exists(parent::DESCRIPTION_KEY, $data)) {
            $description = $data[parent::DESCRIPTION_KEY];
        }

        $layer = new static($layerName, $subDirNames, $description);

        // Add the detected layerObjects to the layer
        foreach ($layerObjects as $key => $keyObjects) {

            foreach ($keyObjects as $object) {
                $objConfig = LayerObjectConfiguration::fromArray($moduleConfigurationFile, $object);
                $layer->addModuleObjectConfiguration($key, $objConfig);
            }
        }

        return $layer;
    }
}
