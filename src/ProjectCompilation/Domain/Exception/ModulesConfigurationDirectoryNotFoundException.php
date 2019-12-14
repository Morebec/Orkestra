<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Exception;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModulesConfigurationDirectory;

class ModulesConfigurationDirectoryNotFoundException extends \Exception
{

    /**
     * ModulesConfigurationDirectoryNotFoundException constructor.
     * @param ModulesConfigurationDirectory $location
     */
    public function __construct(\Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\ModulesConfigurationDirectory $location)
    {
        parent::__construct("The Project's modules configuration directory was not found at '$location', did you compile the project yet? ");
    }
}