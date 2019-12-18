<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\NamespaceVO;

/**
 * A Compilation Request for a ModuleObject
 */
class ModuleObjectCompilationRequest
{
    /**
     * @var ModuleObjectSchema
     */
    private $schema;
    /**
     * @var LayerObjectFile
     */
    private $outFile;
    /**
     * @var NamespaceVO
     */
    private $namespace;

    public function __construct(
        ModuleObjectSchema $schema,
        NamespaceVO $namespace,
        LayerObjectFile $outFile
    ) {
        $this->schema = $schema;
        $this->outFile = $outFile;
        $this->namespace = $namespace;
    }

    /**
     * @return ModuleObjectSchema
     */
    public function getModuleObjectSchema(): ModuleObjectSchema
    {
        return $this->schema;
    }

    /**
     * @return LayerObjectFile
     */
    public function getOutFile(): LayerObjectFile
    {
        return $this->outFile;
    }

    /**
     * @return NamespaceVO
     */
    public function getNamespace(): NamespaceVO
    {
        return $this->namespace;
    }
}
