<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project;

use Assert\Assertion;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;

/**
 * Directory containing the source code of the tests for the *source code* of the app.
 */
class TestsDirectory extends Directory
{
    public function __construct(Path $path)
    {
        Assertion::notBlank((string)$path, "The project's tests directory name cannot be blank");
        parent::__construct($path);
    }
}
