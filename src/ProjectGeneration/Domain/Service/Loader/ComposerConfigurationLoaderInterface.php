<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Loader;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Composer\ComposerConfiguration;
use Morebec\ValueObjects\File\File;

/**
 * Interface for Composer Configuration loader
 */
interface ComposerConfigurationLoaderInterface
{
    /**
     * Loads a Composer Configuration from a file
     * @param File $composerFile
     * @return ComposerConfiguration
     */
    public function load(File $composerFile): ComposerConfiguration;
}
