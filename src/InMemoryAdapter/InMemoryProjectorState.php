<?php

namespace Morebec\Orkestra\InMemoryAdapter;

class InMemoryProjectorState
{
    /** @var string */
    public $typeName;

    /** @var string */
    public $status;

    /** @var string|null */
    public $event;

    public function __construct(string $typeName, string $status, string $event)
    {
        $this->typeName = $typeName;
        $this->status = $status;
        $this->event = $event;
    }
}
