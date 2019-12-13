<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Compiler;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Domain\DomainLayerConfiguration;

/**
 * DomainLayerCompiler
 */
class DomainAbstractLayerCompiler extends AbstractLayerCompiler
{
    protected function mapLayerConfigurationKeyToLayerSubDirectoryName(string $key): string
    {
        if($key == DomainLayerConfiguration::ENTITIES_KEY) {
            $key = 'Model/Entity';
        }

        return parent::mapLayerConfigurationKeyToLayerSubDirectoryName($key);
    }
}
