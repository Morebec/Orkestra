<?php 

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Domain;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\Module;


/**
 * Represents a Module's Domain Layer
 */
class DomainLayer extends AbstractLayer
{
    protected $name = 'Domain';

    public function __construct(Module $module, DomainLayerConfiguration $configuration)
    {
        parent::__construct($module, $configuration);
    }
}