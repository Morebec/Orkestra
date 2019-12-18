<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Infrastructure;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfigurationFile;

class InfrastructureLayerConfiguration extends AbstractLayerConfiguration
{
    const LAYER_NAME = 'Infrastructure';

    public static function fromArray(ModuleConfigurationFile $moduleConfigurationFile, array $data): self
    {
        $subDirNames = [];
        $layerObjects = [];

        foreach ($data as $subDirName => $v) {
            $subDirNames[] = $subDirName;
            foreach ($v as $object) {
                $layerObjects[$subDirName][] = $object;
            }
        }

        $description = null;
        if (array_key_exists(parent::DESCRIPTION_KEY, $data)) {
            $description = $data[parent::DESCRIPTION_KEY];
        }

        $layer = new static(self::LAYER_NAME, $subDirNames, $description);

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
