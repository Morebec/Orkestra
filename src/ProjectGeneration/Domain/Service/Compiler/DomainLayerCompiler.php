<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Compiler;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\AbstractLayer;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\DomainLayer;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\NamespaceVO;
use Morebec\ValueObjects\File\Directory;

/**
 * DomainLayerCompiler
 */
class DomainLayerCompiler extends AbstractLayerCompiler
{
    public function compiler(AbstractLayer $layer)
    {
        $this->createLayerDirectories($layer);
        $this->compilerEntities($layer);
    }
    
    /**
     * 
     * @param AbstractLayer $layer
     */
    private function compileEntities(DomainLayer $layer): void
    {
        $layerNamespace = $layer->getNamespace();
        $entitiesNamespace = $layerNamespace
                                    ->appendString('Model')
                                    ->appendString('Entity');
        $entitiesDirectory = new Directory(
            $layer->getDirectory()->getPath()
                    ->appendString('Model')
                    ->appendString('Entity')
        );
        
        foreach($layer->getEntities() as $entity) {
            $entityConfig = $entity->getConfiguration();
            $this->compileObject($definition, $namespace, $directory);
        }
        
    }
    
    private function compileEventsAndHandlers(DomainLayer $layer): void
    {
        
    }
    
    private function compileCommandsAndHandlers(DomainLayer $layer): void
    {
        
    }
    
    private function compileExceptions(DomainLayer $layer): void
    {
        
    }
    
    private function compileServices(DomainLayer $layer): void
    {
        
    }
    
    private function compileObject(
            ObjectDefinition $definition,
            NamespaceVO $namespace,
            Directory $directory
    ): void
    {
        
    }
}
