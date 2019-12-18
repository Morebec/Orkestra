<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\UseCase;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Domain\DomainLayerConfiguration;

class UseCaseConfiguration
{
    const ENTITY_KEY = 'Entity';

    const COMMAND_KEY = 'Command';

    const EXCEPTION_KEY = 'Exception';

    const EVENT_KEY = 'Event';

    const SERVICE_KEY = 'Service';
    /**
     * @var string
     */
    private $name;

    /**
     * @var UseCaseConfigurationFile
     */
    private $configurationFile;

    /**
     * @var UseCaseObjectConfiguration[]
     */
    private $objectConfigurations;


    /**
     * UseCaseConfiguration constructor.
     * @param string $name name of the use case
     * @param UseCaseConfigurationFile $configurationFile configuration file
     */
    public function __construct(string $name, UseCaseConfigurationFile $configurationFile)
    {
        $this->name = $name;
        $this->configurationFile = $configurationFile;
        $this->objectConfigurations = [];
    }

    public static function fromArray(UseCaseConfigurationFile $configurationFile, array $data): self
    {
        $name = $configurationFile->getDirectory()->getFilename();

        $config = new static($name, $configurationFile);

        if (array_key_exists(self::ENTITY_KEY, $data)) {
            $entities = $data[self::ENTITY_KEY];
            foreach ($entities as $entity) {
                $config->addObjectConfiguration(
                    DomainLayerConfiguration::LAYER_NAME,
                    DomainLayerConfiguration::ENTITIES_KEY,
                    UseCaseObjectConfiguration::fromArray($configurationFile, $entity)
                );
            }
        }

        if (array_key_exists(self::EXCEPTION_KEY, $data)) {
            $exceptions = $data[self::EXCEPTION_KEY];
            foreach ($exceptions as $exception) {
                $config->addObjectConfiguration(
                    DomainLayerConfiguration::LAYER_NAME,
                    DomainLayerConfiguration::EXCEPTIONS_KEY,
                    UseCaseObjectConfiguration::fromArray($configurationFile, $exception)
                );
            }
        }

        if (array_key_exists(self::COMMAND_KEY, $data)) {
            $commands = $data[self::COMMAND_KEY];
            foreach ($commands as $command) {
                $config->addObjectConfiguration(
                    DomainLayerConfiguration::LAYER_NAME,
                    DomainLayerConfiguration::COMMANDS_KEY,
                    UseCaseObjectConfiguration::fromArray($configurationFile, $command)
                );
            }
        }

        if (array_key_exists(self::EVENT_KEY, $data)) {
            $events = $data[self::EVENT_KEY];
            foreach ($events as $event) {
                $config->addObjectConfiguration(
                    DomainLayerConfiguration::LAYER_NAME,
                    DomainLayerConfiguration::EVENTS_KEY,
                    UseCaseObjectConfiguration::fromArray($configurationFile, $event)
                );
            }
        }

        $services = [];
        if (array_key_exists(self::SERVICE_KEY, $data)) {
            $services = $data[self::SERVICE_KEY];
            foreach ($services as $service) {
                $config->addObjectConfiguration(
                    DomainLayerConfiguration::LAYER_NAME,
                    DomainLayerConfiguration::SERVICES_KEY,
                    UseCaseObjectConfiguration::fromArray($configurationFile, $service)
                );
            }
        }

        return $config;
    }

    /**
     * @return UseCaseConfigurationFile
     */
    public function getConfigurationFile(): UseCaseConfigurationFile
    {
        return $this->configurationFile;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $layerName
     * @param string $objectType
     * @param UseCaseObjectConfiguration $object
     */
    private function addObjectConfiguration(string $layerName, string $objectType, UseCaseObjectConfiguration $object): void
    {
        $this->objectConfigurations[$layerName][$objectType][] = $object;
    }

    /**
     * @return UseCaseObjectConfiguration[]
     */
    public function getObjectConfigurations(): array
    {
        return $this->objectConfigurations;
    }
}
