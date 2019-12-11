<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Entity;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Entity\EntitySchemaFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Domain\DomainLayer;
use Morebec\ValueObjects\File\File;

/**
 * EntityConfiguration
 */
class EntityConfiguration
{

    /**
     * @var DomainLayer
     */
    private $layer;

    /**
     * File to the repository of the entity
     * @var File|null
     */
    private $repositoryFile;

    /**
     * @var string|null
     */
    private $subDirectory;

    /**
     * File to the schema of the entity
     * @var File
     */
    private $schemaFile;

    public function __construct(
            DomainLayer $layer,
            EntitySchemaFile $schemaFile,
            ?string $subDirectory,
            ?File $repositoryFile
    )
    {
        $this->schemaFile = $schemaFile;
        $this->subDirectory = $subDirectory;
        $this->repositoryFile = $repositoryFile;
        $this->layer = $layer;
    }
    
    function getRepositoryFile(): ?File
    {
        return $this->repositoryFile;
    }

    function getSubDirectory(): ?string
    {
        return $this->subDirectory;
    }

    function getSchemaFile(): File
    {
        return $this->schemaFile;
    }
}
