<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project;

use Assert\Assertion;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;

/**
 * Directory containing the documentation of the *Project*.
 */
class DocumentationDirectory extends Directory
{
    public function __construct(Path $path)
    {
        Assertion::notBlank((string)$path, "The project's documentation directory name cannot be blank");
        parent::__construct($path);
    }
}
