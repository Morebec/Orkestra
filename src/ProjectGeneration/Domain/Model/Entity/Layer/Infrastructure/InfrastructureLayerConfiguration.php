<?php


namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Infrastructure;


use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\AbstractLayerConfiguration;

class InfrastructureLayerConfiguration extends AbstractLayerConfiguration
{
    const LAYER_NAME = 'Infrastructure';

    public static function fromArray(array $data): self
    {
        $subDirNames = [];
        foreach($data as $subDirName => $v) {
            $subDirNames[] = $subDirName;
        }

        $description = null;
        if(array_key_exists(parent::DESCRIPTION_KEY, $data)) {
            $description = $data[parent::DESCRIPTION_KEY];
        }

        return new static(self::LAYER_NAME, $subDirNames, $description);
    }
}