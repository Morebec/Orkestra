<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Locator;

use Exception;
use Morebec\FileLocator\FileLocator;
use Morebec\FileLocator\FileLocatorStrategy;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project\ProjectConfigurationFile;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;

/**
 * Locates a Project's configuration file according to the
 * a specific starting location
 */
class ProjectConfigurationFileLocator
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
     * @return ProjectConfigurationFile|null
     * @throws Exception
     */
    public function locate(Directory $location): ?ProjectConfigurationFile
    {
        $file = $this->fileLocator->find(
            ProjectConfigurationFile::BASENAME,
            $location,
            FileLocatorStrategy::RECURSIVE_UP()
        );

        if ($file) {
            return new ProjectConfigurationFile(new Path($file));
        }
        return null;
    }
}
