<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer;

use Assert\Assertion;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;

/**
 * Directory containing a layer
 */
class AbstractLayerDirectory extends Directory
{
    public function __construct(Path $path)
    {
        Assertion::notBlank((string)$path, 'A Layer directory must have a valid path');
        parent::__construct($path);
    }
}
