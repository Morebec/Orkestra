<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Service;

use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidProjectConfigurationException;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\ModuleObjectTemplateHandlerNotFoundException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project\ProjectConfiguration;
use Morebec\ValueObjects\File\File;

/**
 * Responsible for finding the handler of a LayerObjectConfigurationTemplate
 */
class ModuleObjectSchemaTemplateHandlerFinder
{
    /**
     * Returns the handler of a template or throws an exception if not found
     * @param ProjectConfiguration $configuration
     * @param string $templateName
     * @return mixed
     * @throws InvalidProjectConfigurationException
     * @throws ModuleObjectTemplateHandlerNotFoundException
     */
    public function getHandler(ProjectConfiguration $configuration, string $templateName): File
    {
        $dir = $configuration->getLayerObjectTemplatesDirectory();

        if (!$dir->exists()) {
            throw new InvalidProjectConfigurationException(
                "Layer Object Templates directory does not exist at '$dir'"
            );
        }

        $handlers = $dir->getFiles();

        foreach ($handlers as $handler) {
            if ($handler->getFilename() === $templateName) {
                return $handler;
            }
        }

        throw new ModuleObjectTemplateHandlerNotFoundException($templateName);
    }
}
