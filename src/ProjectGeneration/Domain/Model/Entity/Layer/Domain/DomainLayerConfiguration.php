<?php


namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\Domain;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\LayerObject\LayerObjectConfiguration;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module\ModuleConfigurationFile;
use Morebec\ValueObjects\Text\Description;
use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer\AbstractLayerConfiguration;

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

    public function __construct(?Description $description)
    {
        parent::__construct(self::LAYER_NAME,  [
            'CommandObject',
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

    public static function fromArray(ModuleConfigurationFile $moduleConfigurationFile, array $data): self
    {
        $description = null;
        if(array_key_exists(parent::DESCRIPTION_KEY, $data)) {
            $description = $data[parent::DESCRIPTION_KEY];
        }
        $configuration = new static($description);

        if(array_key_exists(self::ENTITIES_KEY, $data)) {
            $entities = $data[self::ENTITIES_KEY];
            foreach ($entities as $entity) {
                $objConfig = LayerObjectConfiguration::fromArray($moduleConfigurationFile, $entity);
                $configuration->addLayerObjectConfiguration(self::ENTITIES_KEY, $objConfig);
            }
        }

/*        $valueObjects = $data[self::VALUE_OBJECTS_KEY];
        $commands = $data[self::COMMANDS_KEY];
        $events = $data[self::EVENTS_KEY];
        $exceptions = $data[self::EXCEPTIONS_KEY];
        $services = $data[self::SERVICES_KEY];*/

        return $configuration;
    }
}