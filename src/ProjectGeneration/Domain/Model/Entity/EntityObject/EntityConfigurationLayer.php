<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\EntityObject;



use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Domain\DomainLayer;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Object\LayerObjectConfiguration;

/**
 * EntityConfiguration
 */
class EntityConfigurationLayer extends LayerObjectConfiguration
{
    /**
     * File to the repository of the entity
     * @var EntityRepositorySchemaFile|null
     */
    private $repositoryFile;

    private function __construct(
        DomainLayer $layer,
        EntitySchemaFileLayer $schemaFile,
        ?string $subDirectory,
        ?EntityRepositorySchemaFile $repositoryFile
    )
    {
        $this->repositoryFile = $repositoryFile;
        parent::__construct($layer, $schemaFile, $subDirectory);
    }

    /**
     * Returns the repository SchemaFile or null if none set
     * @return EntityRepositorySchemaFile|null
     */
    function getRepositoryFile(): ?EntityRepositorySchemaFile
    {
        return $this->repositoryFile;
    }
}
