<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\Generic;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayer;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer\AbstractLayerConfiguration;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\Module;

/**
 * Represents a Generic Module Layer
 */
final class GenericLayer extends AbstractLayer
{
    public function __construct(Module $module, AbstractLayerConfiguration $configuration)
    {
        parent::__construct($module, $configuration);
    }

    public function getName(): string
    {
        return $this->getConfiguration()->getName();
    }
}
