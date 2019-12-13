<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Exception;

use Exception;
use Morebec\ValueObjects\File\Path;

/**
 * ProjectConfigFileNotFoundException
 */
class ProjectConfigurationFileNotFoundException extends Exception
{
    public function __construct(Path $pathToFile)
    {
        parent::__construct("Project config file was not found at '$pathToFile'");
    }
}
