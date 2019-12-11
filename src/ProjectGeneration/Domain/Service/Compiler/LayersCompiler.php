<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Compiler;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\AbstractLayer;

/**
 * Compiler for layers.
 * This serves more as a dispatcher for the specific LayerCompilers.
 * Every layer type has its own compiler
 */
class LayersCompiler
{
    /**
     * Array of compiler, where the key is the compiler name
     * and the value the compiler
     * @var AbstractLayerCompiler[]
     */
    private $compilers;
    
    public function __construct()
    {
        
    }
    
    /**
     * Compiles a layer
     * @param AbstractLayer $layer
     */
    public function compileLayer(AbstractLayer $layer)
    {
        $compiler = $layer->getCompilerForLayer($layer);
        $compiler->compile($layer);
    }
    
    private function getCompilerForLayer(
            AbstractLayer $layer
    ): AbstractLayerCompiler
    {
        return $this->compilers[$layer->getName()];
    }
}
