<?php


namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Domain;


use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Entity\EntityConfiguration;
use Morebec\ValueObjects\Text\Description;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\AbstractLayerConfiguration;
use ProjectGeneration\Domain\Model\Entity\Object\ObjectConfiguration;

/**
 * Corresponds to the configuration of the DomainLayer inside the ModuleConfiguration
 * @package ProjectGeneration\Domain\Model\Entity\Layer
 */
class DomainLayerConfiguration extends AbstractLayerConfiguration
{
    public const LAYER_NAME = 'Domain';

    public const ENTITIES_KEY = 'entities';
    public const VALUE_OBJECTS_KEY = 'value_objects';
    public const COMMANDS_KEY = 'commands';
    public const EVENTS_KEY = 'events';
    public const EXCEPTIONS_KEY = 'exceptions';
    public const SERVICES_KEY = 'services';

    /**
     * @var EntityConfiguration[]
     */
    private $entityConfigurations;

    /**
     * @var ObjectConfiguration[]
     */
    private $valueObjectsConfigurations;

    /**
     * @var ObjectConfiguration[]
     */
    private $commandConfigurations;

    /**
     * @var ObjectConfiguration[]
     */
    private $eventConfigurations;

    /**
     * @var ObjectConfiguration[]
     */
    private $exceptionConfigurations;

    /**
     * @var ObjectConfiguration[]
     */
    private $serviceConfigurations;


    public function __construct(?Description $description)
    {
        parent::__construct(self::LAYER_NAME,  [
            'Command',
            'CommandHandler',
            'Exception',
            'Event',
            'EventHandler',
            'Model',
            'Model/Entity',
            'Model/Factory',
            'Model/Repository',
            'Service'
        ], $description);
    }

    /**
     * @return EntityConfiguration[]
     */
    public function getEntityConfigurations(): array
    {
        return $this->entityConfigurations;
    }

    /**
     * @param EntityConfiguration[] $entityConfigurations
     */
    public function setEntityConfigurations(array $entityConfigurations): void
    {
        $this->entityConfigurations = $entityConfigurations;
    }

    /**
     * @return ObjectConfiguration[]
     */
    public function getValueObjectsConfigurations(): array
    {
        return $this->valueObjectsConfigurations;
    }

    /**
     * @param ObjectConfiguration[] $valueObjectsConfigurations
     */
    public function setValueObjectsConfigurations(array $valueObjectsConfigurations): void
    {
        $this->valueObjectsConfigurations = $valueObjectsConfigurations;
    }

    /**
     * @return ObjectConfiguration[]
     */
    public function getCommandConfigurations(): array
    {
        return $this->commandConfigurations;
    }

    /**
     * @param ObjectConfiguration[] $commandConfigurations
     */
    public function setCommandConfigurations(array $commandConfigurations): void
    {
        $this->commandConfigurations = $commandConfigurations;
    }

    /**
     * @return ObjectConfiguration[]
     */
    public function getEventConfigurations(): array
    {
        return $this->eventConfigurations;
    }

    /**
     * @param ObjectConfiguration[] $eventConfigurations
     */
    public function setEventConfigurations(array $eventConfigurations): void
    {
        $this->eventConfigurations = $eventConfigurations;
    }

    /**
     * @return ObjectConfiguration[]
     */
    public function getExceptionConfigurations(): array
    {
        return $this->exceptionConfigurations;
    }

    /**
     * @param ObjectConfiguration[] $exceptionConfigurations
     */
    public function setExceptionConfigurations(array $exceptionConfigurations): void
    {
        $this->exceptionConfigurations = $exceptionConfigurations;
    }

    /**
     * @return ObjectConfiguration[]
     */
    public function getServiceConfigurations(): array
    {
        return $this->serviceConfigurations;
    }

    /**
     * @param ObjectConfiguration[] $serviceConfigurations
     */
    public function setServiceConfigurations(array $serviceConfigurations): void
    {
        $this->serviceConfigurations = $serviceConfigurations;
    }



    public static function fromArray(array $data): self
    {
        $entities = $data[self::ENTITIES_KEY];
        $valueObjects = $data[self::VALUE_OBJECTS_KEY];
        $commands = $data[self::COMMANDS_KEY];
        $events = $data[self::EVENTS_KEY];
        $exceptions = $data[self::EXCEPTIONS_KEY];
        $services = $data[self::SERVICES_KEY];

        $description = null;
        if(array_key_exists(parent::DESCRIPTION_KEY, $data)) {
            $description = $data[parent::DESCRIPTION_KEY];
        }

        return new static($description);
    }
}