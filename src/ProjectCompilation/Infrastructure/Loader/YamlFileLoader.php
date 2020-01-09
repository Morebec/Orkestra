<?php

namespace Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader;

use Assert\Assertion;
use Morebec\ValueObjects\File\File;
use Symfony\Component\Yaml\Yaml;

/**
 * Loads files
 */
class YamlFileLoader
{
    /**
     * Loads a file and returns its data as an associative array
     * @param File $file
     * @return array
     */
    public function loadFile(File $file): array
    {
        Assertion::true($file->exists());
        $contents = Yaml::parse($file->getContent());
        Assertion::isArray($contents, "Expected an array at $file");
        return $contents;
    }
}
