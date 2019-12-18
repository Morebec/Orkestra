<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Project;

use Assert\Assertion;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\OCFile;
use Morebec\ValueObjects\File\Path;

/**
 * Corresponds to the OC File containing the configuration for the project
 * itself. It is named `orkestra.oc` and is always located at the root of
 * the project's directory.
 */
class ProjectConfigurationFile extends OCFile
{
    public const BASENAME = 'orkestra.' . parent::EXTENSION;

    public function __construct(Path $path)
    {
        parent::__construct($path);

        $basename = $this->getBasename();
        Assertion::same(
            self::BASENAME,
            $basename,
            "A Project's configuration file must be named: '" .
                self::BASENAME . "', '$basename' found."
        );
    }
}
