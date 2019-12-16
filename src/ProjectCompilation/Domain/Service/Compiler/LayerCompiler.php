<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Compiler;

use Morebec\ObjectGenerator\Domain\Compiler\PHPObjectCompiler;
use Morebec\ObjectGenerator\Domain\Exception\FileNotFoundException;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidLayerObjectSchemaException;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidProjectConfigurationException;
use Morebec\Orkestra\ProjectCompilation\Domain\Exception\LayerObjectTemplateHandlerNotFoundException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectCompilationRequest;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectSchema;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader\LayerObjectSchemaDataLoaderInterface;
use Morebec\ValueObjects\File\Path;
use Psr\Log\LoggerInterface;
use Stringy\Stringy as Str;

/**
 * A Layer compiler is responsible for compiling all the resources of a layer 
 * and creating the necessary directories to support them.
 */
class LayerCompiler
{
    /**
     * @var LayerObjectCompiler
     */
    protected $objectCompiler;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var LayerObjectSchemaDataLoaderInterface
     */
    private $objectSchemaLoader;

    /**
     * List of Layer Object Compilation requests to compile
     * @var array
     */
    private $objectRequestQueue;

    public function __construct(
        LayerObjectCompiler $objectCompiler,
        LayerObjectSchemaDataLoaderInterface $objectSchemaLoader,
        LoggerInterface $logger
    )
    {
        $this->objectCompiler = $objectCompiler;
        $this->logger = $logger;
        $this->objectSchemaLoader = $objectSchemaLoader;
    }

    /**
     * Compiles a layer
     * @param AbstractLayer $layer
     * @throws InvalidLayerObjectSchemaException
     * @throws FileNotFoundException
     * @throws InvalidProjectConfigurationException
     * @throws LayerObjectTemplateHandlerNotFoundException
     */
    public function compile(AbstractLayer $layer)
    {
        // Add Objects to compile queue
        $layerConfiguration = $layer->getConfiguration();
        /** @var LayerObjectConfiguration $objectConfiguration */
        foreach ($layerConfiguration->getLayerObjectConfigurations() as $key => $objectConfigurations) {
            foreach ($objectConfigurations as $objConfig) {
                $key = $this->mapLayerConfigurationKeyToLayerSubDirectoryName($key);
                $this->objectCompiler->enqueueObject($layer, $objConfig, $key);
            }
        }

        // Compile Queue
        $this->objectCompiler->compileQueue();
    }

    /**
     * Maps a layer's configuration key to a layer's sub directory
     * @param string $key
     * @return string
     */
    protected function mapLayerConfigurationKeyToLayerSubDirectoryName(string $key): string
    {
        return Str::create($key)->upperCaseFirst();
    }
}
