<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Locator;

use Morebec\FileLocator\FileLocator;
use Morebec\FileLocator\FileLocatorStrategy;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Composer\ComposerConfigurationFile;
use Morebec\ValueObjects\File\Directory;

/**
 * Locates a Composer configuration file according to the 
 * a specific starting location
 */
class ComposerConfigurationFileLocator
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
     * Locates the Project's Configuration file
     * from a specific location and going up
     * @param Directory $location
     * @return ComposerConfigurationFile
     * @throws \Exception
     */
    public function locate(Directory $location): ComposerConfigurationFile
    {
        $file = $this->fileLocator->find(
                ComposerConfigurationFile::BASENAME,
                $location,
                FileLocatorStrategy::RECURSIVE_UP()
        );
        if($file !== null) {
            return new ComposerConfigurationFile($file->getRealpath());
        }
        
        return null;
    }
}
