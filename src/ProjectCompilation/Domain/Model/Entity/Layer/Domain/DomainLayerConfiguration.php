<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Domain;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfigurationFile;
use Morebec\ValueObjects\Text\Description;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayerConfiguration;

/**
 * Corresponds to the configuration of the DomainLayer inside the ModuleConfiguration
 * @package ProjectGeneration\Domain\Model\Entity\Layer
 */
class DomainLayerConfiguration extends AbstractLayerConfiguration
{
    public const LAYER_NAME = 'Domain';

    public const ENTITIES_KEY = 'Entity';

    public const VALUE_OBJECTS_KEY = 'ValueObject';

    public const COMMANDS_KEY = 'Command';

    public const EVENTS_KEY = 'Event';

    public const EXCEPTIONS_KEY = 'Exception';

    public const SERVICES_KEY = 'Service';

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

    public static function fromArray(ModuleConfigurationFile $moduleConfigurationFile, array $data): self
    {
        $description = null;
        if(array_key_exists(parent::DESCRIPTION_KEY, $data)) {
            $description = $data[parent::DESCRIPTION_KEY];
        }
        $layerConfiguration = new static($description);

        $subDirNames = [];
        $layerObjects = [];

        foreach($data as $subDirName => $v) {
            $subDirNames[] = $subDirName;
            foreach($v as $object) {
                $layerObjects[$subDirName][] = $object;
            }
        }

        $specialKeys = [
            self::ENTITIES_KEY,
            self::VALUE_OBJECTS_KEY,
            self::COMMANDS_KEY
        ];

        // Add the detected layerObjects to the layer
        foreach ($layerObjects as $key => $keyObjects) {
            if(in_array($key, $specialKeys)) continue;
            foreach($keyObjects as $object) {
                $objConfig = LayerObjectConfiguration::fromArray($moduleConfigurationFile, $object);
                $layerConfiguration->addLayerObjectConfiguration($key, $objConfig);
            }
        }

        // Handle Special Cases
        if(array_key_exists(self::ENTITIES_KEY, $data)) {
            $entities = $data[self::ENTITIES_KEY];
            foreach ($entities as $entity) {
                $objConfig = LayerObjectConfiguration::fromArray($moduleConfigurationFile, $entity);
                $layerConfiguration->addLayerObjectConfiguration(self::ENTITIES_KEY, $objConfig);
            }
        }

        if(array_key_exists(self::VALUE_OBJECTS_KEY, $data)) {
            $entities = $data[self::VALUE_OBJECTS_KEY];
            foreach ($entities as $entity) {
                $objConfig = LayerObjectConfiguration::fromArray($moduleConfigurationFile, $entity);
                $layerConfiguration->addLayerObjectConfiguration(self::ENTITIES_KEY, $objConfig);
            }
        }

        if(array_key_exists(self::COMMANDS_KEY, $data)) {
            $entities = $data[self::COMMANDS_KEY];
            foreach ($entities as $entity) {
                $objConfig = LayerObjectConfiguration::fromArray($moduleConfigurationFile, $entity);
                $layerConfiguration->addLayerObjectConfiguration(self::COMMANDS_KEY, $objConfig);
            }
        }


        return $layerConfiguration;
    }
}