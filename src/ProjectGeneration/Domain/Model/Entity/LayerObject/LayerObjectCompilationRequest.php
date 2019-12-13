<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\NamespaceVO;

/**
 * A Compilation Request for a LayerObject
 */
class LayerObjectCompilationRequest
{
    /**
     * @var LayerObjectSchema
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
        LayerObjectSchema $schema,
        NamespaceVO $namespace,
        LayerObjectFile $outFile
    )
    {
        $this->schema = $schema;
        $this->outFile = $outFile;
        $this->namespace = $namespace;
    }

    /**
     * @return LayerObjectSchema
     */
    public function getLayerObjectSchema(): LayerObjectSchema
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