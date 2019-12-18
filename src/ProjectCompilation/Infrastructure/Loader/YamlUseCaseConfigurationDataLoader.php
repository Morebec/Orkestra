<?php


namespace Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\UseCase\UseCaseConfigurationFile;
use Morebec\Orkestra\ProjectCompilation\Domain\Service\Loader\UseCaseConfigurationDataLoaderInterface;

/**
 *  Yaml Implementation of UseCaseConfigurationDataLoaderInterface
 */
class YamlUseCaseConfigurationDataLoader extends YamlFileLoader implements UseCaseConfigurationDataLoaderInterface
{
    /**
     * @var YamlFileLoader
     */
    private $loader;

    public function __construct(YamlFileLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @inheritDoc
     * @throws InvalidUseCaseConfigurationException
     */
    public function loadDataFromFile(UseCaseConfigurationFile $file): array
    {
        $data = $this->loader->loadFile($file);

        if (!is_array($data)) {
            throw new InvalidUseCaseConfigurationException(
                "Invalid Module Configuration: It should start with the name of the module followed by its definition at $file"
            );
        }

        return $data;
    }
}
