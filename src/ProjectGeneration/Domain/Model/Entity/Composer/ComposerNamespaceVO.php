<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Composer;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\NamespaceVO;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\ValueObjectInterface;

/**
 * Composer holds the values for namespaces
 * by associating the namespace with a directory
 * This VO represents this fact.
 */
class ComposerNamespaceVO implements ValueObjectInterface
{
    /**
     * @var Directory
     */
    private $directory;

    /**
     * @var NamespaceVO
     */
    private $namespace;

    public function __construct(NamespaceVO $namespace, Directory $directory)
    {
        $this->namespace = $namespace;
        $this->directory = $directory;
    }
    
    function getDirectory(): Directory
    {
        return $this->directory;
    }

    function getNamespace(): NamespaceVO
    {
        return $this->namespace;
    }

    public function __toString(): string
    {
        return (string)$this->namespace;
    }

    public function isEqualTo(ValueObjectInterface $valueObject): bool
    {
        return (string)$this == (string)$valueObject;
    }
}
