<?php


namespace Morebec\Orkestra\ProjectCompilation\Infrastructure\Compiler;


use Morebec\ObjectGenerator\Domain\ObjectDumper;
use Morebec\ObjectGenerator\Domain\ObjectGenerator;
use Morebec\ObjectGenerator\Infrastructure\Loader\YamlDefinitionLoader;
use Morebec\ObjectGenerator\Infrastructure\ObjectReferenceDumper\YamlObjectReferenceDumper;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectCompilationRequest;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectSchemaFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\NamespaceVO;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Compiler\LayerObjectCompilerInterface;
use Morebec\ValueObjects\File\File;
use Stringy\Stringy;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Compiles Layer Object from Layer Object Schema files in Yaml format
 */
class LayerObjectYamlSchemaCompiler implements LayerObjectCompilerInterface
{
    /**
     * @var ObjectGenerator
     */
    private $objectGenerator;

    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct()
    {
        $this->objectGenerator = new ObjectGenerator(
            new YamlDefinitionLoader(),
            new YamlObjectReferenceDumper()
        );
    }

    /**
     * @inheritDoc
     */
    public function compileObject(LayerObjectCompilationRequest $request): void
    {
        $result = $this->objectGenerator->generateFromObjectDefinition($request->getLayerObjectSchema());
        // Convert to code
        $objectDumper = new ObjectDumper();
        $code = $objectDumper->dump($result->getDefinition(), $result->getObject());

        // Write content
        file_put_contents((string)$request->getOutFile(), $code);
    }
}