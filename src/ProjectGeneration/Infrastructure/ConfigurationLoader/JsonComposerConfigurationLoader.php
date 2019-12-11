<?php

namespace Morebec\Orkestra\ProjectGeneration\Infrastructure\ConfigurationLoader;


use Assert\Assertion;
use Assert\AssertionFailedException;
use Exception;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Composer\ComposerConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Composer\ComposerNamespaceVO;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\NamespaceVO;
use Morebec\Orkestra\ProjectGeneration\Domain\Service\Loader\ComposerConfigurationLoaderInterface;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\File;
use Morebec\ValueObjects\File\Path;
use Stringy\Stringy as Str;

/**
 * The Composer config loader is responsible
 * for loading composer.json files and
 * generate a ComposerConfig class
 */
class JsonComposerConfigurationLoader implements ComposerConfigurationLoaderInterface
{
    /**
     * @inheritDoc
     * @param File $composerFile
     * @return ComposerConfiguration
     * @throws AssertionFailedException
     */
    public function load(File $composerFile): ComposerConfiguration
    {
        Assertion::true(
            $composerFile->exists(),
            "Cannot load composer.json, file does not exist at '$composerFile'"
        );

        $data = json_decode($composerFile->getContent(), true);

        $composerConfiguration = new ComposerConfiguration();

        foreach ($data['autoload']['psr-4'] as $ns => $dir) {
            if(Str::create($ns)->endsWith('\\')) {
                $ns = (string)Str::create($ns)->removeRight('\\');
            }
            $directory = Directory::fromStringPath(
                $composerFile->getDirectory() . '/' . $dir
            );
            $namespace = new ComposerNamespaceVO(new NamespaceVO($ns), new Directory(new Path($directory)));

            $composerConfiguration->addPsr4Namespace($namespace);
        }

        return $composerConfiguration;
    }
}
