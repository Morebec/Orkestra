<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Locator;

use Exception;
use Morebec\FileLocator\FileLocator;
use Morebec\FileLocator\FileLocatorStrategy;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Composer\ComposerConfigurationFile;
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
     * or returns null if it could not be found
     * @param Directory $location
     * @return ComposerConfigurationFile|null
     * @throws Exception
     */
    public function locate(Directory $location): ?ComposerConfigurationFile
    {
        $file = $this->fileLocator->find(
            ComposerConfigurationFile::BASENAME,
            $location,
            FileLocatorStrategy::RECURSIVE_UP()
        );
        if ($file !== null) {
            return new ComposerConfigurationFile($file->getRealpath());
        }
        
        return null;
    }
}
