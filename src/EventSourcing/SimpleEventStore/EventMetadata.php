<?php

namespace Morebec\Orkestra\EventSourcing\SimpleEventStore;

use Morebec\Orkestra\EventSourcing\EventStore\EventMetadataInterface;

/**
 * Represents additional data stored about an event outside of its
 * schema.
 */
class EventMetadata implements EventMetadataInterface
{
    /** @var mixed[] */
    private $data;

    /**
     * EventMetadata constructor.
     *
     * @param mixed[] $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Add a new value to this metadata.
     * If the key already exists, it gets overwritten.
     *
     * @param $value
     */
    public function putValue(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Indicates if a key exists within this metadata.
     */
    public function hasKey(string $key): bool
    {
        return \array_key_exists($key, $this->data);
    }

    /**
     * Removes a key from this metadata.
     * If the key does not exists, silently returns.
     */
    public function removeKey(string $key): void
    {
        unset($this->data[$key]);
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
