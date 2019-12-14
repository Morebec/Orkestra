<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Application;

use Morebec\Orkestra\ProjectCompilation\Domain\Exception\InvalidModuleConfigurationException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModuleConfigurationFile;
use Morebec\ValueObjects\Text\Description;
use Stringy\Stringy as Str;

class ApplicationLayerConfiguration extends AbstractLayerConfiguration
{
    public const LAYER_NAME = 'Application';

    public function __construct(array $subDirectoryNames, ?Description $description)
    {
        parent::__construct(self::LAYER_NAME, $subDirectoryNames, $description);
    }

    /**
     * Constructs an Application Layer Configuration Object from an array representation
     * coming from the data read in the ModuleConfigurationFile
     * @param ModuleConfigurationFile $moduleConfigurationFile
     * @param array $data
     * @return static
     * @throws InvalidModuleConfigurationException
     */
    public static function fromArray(ModuleConfigurationFile $moduleConfigurationFile, array $data): self
    {
        $applicationNames = [];
        $layerObjects = [];

        // Load Object Configurations
        // In the Application Layer Configuration inside the Module Configuration
        // Each Key under Application corresponds to an Application
        // And under each application there is a sub directory for the
        // organization of objects
        foreach($data as $applicationName => $organizationalSubDirectories) {
            // The description key is at the same level as applications
            if($applicationName === parent::DESCRIPTION_KEY) continue;
            // Ensure Application is Uppercase
            $applicationName = (string)Str::create($applicationName)->upperCaseFirst();
            $applicationNames[] = $applicationName;
            foreach($organizationalSubDirectories as $organizationalSubDirectory => $objects) {
                // Ensure Organization Sub directory is Uppercase
                $organizationalSubDirectory = (string)Str::create($organizationalSubDirectory)->upperCaseFirst();
                foreach($objects as $object) {
                    $key = "$applicationName/$organizationalSubDirectory";
                    $layerObjects[$key][] = $object;
                }
            }
        }

        // Detect Description if Any
        $description = null;
        if(array_key_exists(parent::DESCRIPTION_KEY, $data)) {
            $description = $data[parent::DESCRIPTION_KEY];
        }

        // Create Later
        $layer = new static($applicationNames, $description);

        // Add the detected objects to the layer
        foreach ($layerObjects as $key => $keyObjects) {
            foreach($keyObjects as $object) {
                $objConfig = LayerObjectConfiguration::fromArray($moduleConfigurationFile, $object);
                $layer->addLayerObjectConfiguration($key, $objConfig);
            }
        }

        return $layer;
    }
}