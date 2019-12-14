<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Event;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Module\Module;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * ModuleCompiledEvent
 */
class ModuleCompiledEvent extends Event
{
    /**
     * @var Module
     */
    private $module;

    public function __construct(Module $module)
    {
        $this->module = $module;
    }
    
    function getModule(): Module
    {
        return $this->module;
    }
}
