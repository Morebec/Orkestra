<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Application;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\AbstractLayerConfiguration;
use Morebec\ValueObjects\Text\Description;

class ApplicationLayerConfiguration extends AbstractLayerConfiguration
{
    public const LAYER_NAME = 'Application';

    public function __construct(array $subDirectoryNames, ?Description $description)
    {
        parent::__construct(self::LAYER_NAME, [
            'Web',
            'Console',
            'REST'
        ], $description);
    }

    public static function fromArray(array $data): self
    {
        $subDirNames = [];
        foreach($data as $subDirName) {
            $subDirNames[] = $subDirName;
        }

        $description = null;
        if(array_key_exists(parent::DESCRIPTION_KEY, $data)) {
            $description = $data[parent::DESCRIPTION_KEY];
        }

        return new static($subDirNames, $description);
    }
}