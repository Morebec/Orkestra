<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module;

use Assert\Assertion;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\OCFile;
use Morebec\ValueObjects\File\Path;

/**
 * Corresponds to an *OC File* containing the *Configuration of a Module*,
 * which determines where that *Module* is located in the Source Directory,
 * how it is named, and the different components it should contain.
 * The file is always named `module.oc`.
 */
class ModuleConfigurationFile extends OCFile
{
    public const BASENAME = 'module.' . parent::EXTENSION;
    
    public function __construct(Path $path)
    {
        parent::__construct($path);
        
        $basename = $this->getBasename();
        Assertion::same(
            self::BASENAME,
            $basename,
            "A Module configuration file must be named: " .
                self::BASENAME . " '$basename' found."
        );
    }
}
