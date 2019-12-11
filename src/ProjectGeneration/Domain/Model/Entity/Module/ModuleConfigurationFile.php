<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module;

use Assert\Assertion;
use Morebec\ValueObjects\File\Path;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\OCFile;
/**
 * Corresponds to an *OC File* containing the *Configuration of a Module*, 
 * determining where that *Module* is located, how it is named, and the 
 * different components it should contain. The file is always named `module.oc`.
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
                self::BASENAME . "'$basename' found."
        );
    }
}
