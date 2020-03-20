<?php

namespace Tests\Morebec\Orkestra\EventSourcing;

use Morebec\Orkestra\Messaging\Event\EventInterface;

class TestEvent implements EventInterface
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
