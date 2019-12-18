<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Compiler;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Domain\DomainLayerConfiguration;

/**
 * DomainLayerCompiler
 */
class DomainLayerCompiler extends LayerCompiler
{
    protected function mapLayerConfigurationKeyToLayerSubDirectoryName(string $key): string
    {
        if ($key == DomainLayerConfiguration::ENTITIES_KEY) {
            $key = 'Model/Entity';
        }

        return parent::mapLayerConfigurationKeyToLayerSubDirectoryName($key);
    }
}
