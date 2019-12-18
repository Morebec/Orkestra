<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Composer;

use Assert\Assertion;
use Morebec\ValueObjects\File\File;
use Morebec\ValueObjects\File\Path;

/**
 * File containing the composer configuration
 */
class ComposerConfigurationFile extends File
{
    public const BASENAME = 'composer.json';
    
    public function __construct(Path $path)
    {
        parent::__construct($path);
        
        $basename = $this->getBasename();
        Assertion::same(
            $basename,
            self::BASENAME,
            'Composer configuration file must be named' . self::BASENAME,
            "'$basename' found"
        );
    }
}
