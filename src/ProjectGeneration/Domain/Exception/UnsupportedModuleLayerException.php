<?php


namespace Morebec\Orkestra\ProjectGeneration\Domain\Exception;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module\ModuleConfigurationFile;

/**
 * Thrown when a layer is specified in a module configuration file is not supported by
 * Orkestra
 */
class UnsupportedModuleLayerException extends InvalidModuleConfigurationException
{
    public function __construct(ModuleConfigurationFile $moduleConfigurationFile, string $layerName)
    {
        parent::__construct("Layer $layerName is not supported as a module layer at $moduleConfigurationFile");
    }
}