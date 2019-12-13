<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Compiler;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject\LayerObjectCompilationRequest;

/**
 * Compiles Layer Object Schema Files
 */
interface LayerObjectCompilerInterface
{
    /**
     * Executes a LayerObjectCompilationRequest
     * @param LayerObjectCompilationRequest $request
     */
    public function compileObject(LayerObjectCompilationRequest $request): void;
}
