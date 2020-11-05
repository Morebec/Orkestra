<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\EventStore\EventStoreSubscriptionIdInterface;

class EventStoreSubscriptionId implements EventStoreSubscriptionIdInterface
{
    /**
     * @var string
     */
    private $value;

    private function __construct(string $identifier)
    {
        $this->value = $identifier;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function fromString(string $identifier): EventStoreSubscriptionIdInterface
    {
        return new self($identifier);
    }

    public function isEqualTo(EventStoreSubscriptionIdInterface $identifier): bool
    {
        if ($identifier instanceof self) {
            return $this->value === $identifier->value;
        }

        return (string) $this === (string) $identifier;
    }
}
