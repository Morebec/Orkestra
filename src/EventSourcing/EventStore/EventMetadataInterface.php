<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

/**
 * Interface for Event Metadata when saving the event to the event store.
 */
interface EventMetadataInterface
{
    /**
     * Removes a key from this metadata.
     * If the key does not exists, silently returns.
     */
    public function removeKey(string $key): void;

    /**
     * Indicates if a key exists within this metadata.
     */
    public function hasKey(string $key): bool;

    /**
     * Add a new value to this metadata.
     * If the key already exists, it gets overwritten.
     *
     * @param $value
     */
    public function putValue(string $key, $value): void;

    /**
     * Returns an array representation of this metadata.
     */
    public function toArray(): array;
}
