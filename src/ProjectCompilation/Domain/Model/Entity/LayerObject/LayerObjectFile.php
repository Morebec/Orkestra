<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject;

use Assert\Assertion;
use Morebec\ValueObjects\File\File;
use Morebec\ValueObjects\File\Path;

/**
 * Represents the PHP file where a LayerObject is compiled to
 */
class LayerObjectFile extends File
{
    public const EXTENSION = 'php';

    public function __construct(Path $path)
    {
        Assertion::endsWith(
            (string)$path,
            self::EXTENSION,
            "A PHP File must end with the '" . self::EXTENSION . "' extension at '$path'"
        );
        parent::__construct($path);
    }

    /**
     * Makes a new instance of LayerObjectFile
     * @param Path $path
     * @return LayerObjectFile
     */
    public static function makeFromPath(Path $path): self
    {
        return new static($path);
    }
}
