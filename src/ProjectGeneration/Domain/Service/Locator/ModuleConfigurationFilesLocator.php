<?php


namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Locator;


use Morebec\FileLocator\FileLocator;
use Morebec\FileLocator\FileLocatorStrategy;
use Morebec\Orkestra\ProjectGeneration\Domain\Exception\ModulesConfigurationDirectoryNotFoundException;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module\ModuleConfigurationFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module\ModulesConfigurationDirectory;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Project\ProjectConfigurationFile;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\File;
use Morebec\ValueObjects\File\Path;

class ModuleConfigurationFilesLocator
{
    /**
     * @var FileLocator
     */
    private $fileLocator;

    public function __construct()
    {
        $this->fileLocator = new FileLocator();
    }

    /**
     * Locates the Modules's Configuration file
     * from a project's directory
     * @param ModulesConfigurationDirectory $location
     * @return ModuleConfigurationFile[]
     */
    public function locate(Directory $location): array
    {
        if(!$location->exists()) {
            return [];
        }

        $files = $this->fileLocator->findAll(
            ModuleConfigurationFile::BASENAME,
            $location,
            FileLocatorStrategy::RECURSIVE_DOWN()
        );

        return array_map(static function(File $file){

            return new ModuleConfigurationFile(new Path($file->getRealPath()));
        }, $files);
    }
}