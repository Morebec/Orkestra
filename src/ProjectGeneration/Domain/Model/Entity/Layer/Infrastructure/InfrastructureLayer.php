<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Infrastructure;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\Path;

/**
 * Represents a Module's Application Layer
 */
final class InfrastructureLayer extends AbstractLayer
{
    protected $name = 'Infrastructure';
}

