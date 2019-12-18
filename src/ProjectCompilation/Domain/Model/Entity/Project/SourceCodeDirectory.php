<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project;

use Assert\Assertion;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;

/**
 * Directory containing the source code of the Orkestra based App.
 * It is usually called `src` and is located at the root of the project's
 * *Code base* directory.
 * (although that can be configured using the *Project* configuration)
 */
class SourceCodeDirectory extends Directory
{
    public function __construct(Path $path)
    {
        Assertion::notBlank((string)$path, "The project's source directory name cannot be blank");
        parent::__construct($path);
    }
}
