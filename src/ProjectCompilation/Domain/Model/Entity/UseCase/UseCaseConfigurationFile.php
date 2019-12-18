<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\UseCase;

use Assert\Assertion;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\OCFile;
use Morebec\ValueObjects\File\Path;

/**
 * Corresponds to an *OC File* containing the *Configuration of a Use Case*,
 * which determining its dependencies.
 * The file is always named `use_case.oc`.
 */
class UseCaseConfigurationFile extends OCFile
{
    public const BASENAME = 'use_case.' . parent::EXTENSION;

    public function __construct(Path $path)
    {
        parent::__construct($path);

        $basename = $this->getBasename();
        Assertion::same(
            self::BASENAME,
            $basename,
            "A Use Case configuration file must be named: " .
            self::BASENAME . " '$basename' found."
        );
    }
}
