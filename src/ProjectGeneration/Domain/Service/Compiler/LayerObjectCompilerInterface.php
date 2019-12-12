<?php


namespace Morebec\Orkestra\ProjectGeneration\Domain\Service\Compiler;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject\LayerObjectSchemaFile;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\NamespaceVO;
use Morebec\ValueObjects\File\File;

/**
 * Compiles Layer Object Schema Files
 */
interface LayerObjectCompilerInterface
{
    /**
     * Compiles a LayerObjectSchemaFile to a an LayerObjectFile and applies a given namespace
     * @param LayerObjectSchemaFile $schemaFile
     * @param NamespaceVO $namespace
     * @param File $targetFile
     */
    public function compileSchemaFileWithNamespaceToFile(
        LayerObjectSchemaFile $schemaFile,
        NamespaceVO $namespace,
        File $targetFile
    ): void;
}