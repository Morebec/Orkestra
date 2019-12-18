<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity;

use Assert\Assertion;
use Morebec\ValueObjects\File\File;
use Morebec\ValueObjects\File\Path;

/**
 * An Orkestra configuration file.
 * They are currently stored as YAML files, but have the .oc extension
 * (stands for Orkestra Configuration), to distinguish them from other
 * unrelated YAML files.
 */
class OCFile extends File
{
    public const EXTENSION = 'oc';

    public function __construct(Path $path)
    {
        Assertion::endsWith(
            (string)$path,
            self::EXTENSION,
            "An OC File must end with the '" . self::EXTENSION . "' extension at '$path'"
        );
        parent::__construct($path);
    }

    /**
     * Makes a new instance of OCFile
     * @param Path $path
     * @return OCFile
     */
    public static function makeFromPath(Path $path): self
    {
        return new static($path);
    }
}
