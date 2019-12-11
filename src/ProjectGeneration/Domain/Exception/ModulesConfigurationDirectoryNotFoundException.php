<?php

namespace Morebec\Orkestra\ProjectGeneration\Domain\Exception;

use Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module\ModulesConfigurationDirectory;

class ModulesConfigurationDirectoryNotFoundException extends \Exception
{

    /**
     * ModulesConfigurationDirectoryNotFoundException constructor.
     * @param ModulesConfigurationDirectory $location
     */
    public function __construct(\Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Module\ModulesConfigurationDirectory $location)
    {
        parent::__construct("The Project's modules configuration directory does not exist at '$location', did you compile the project yet? ");
    }
}