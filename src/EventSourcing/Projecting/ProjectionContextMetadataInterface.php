<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

/**
 * Represents Metadata about a given Projection Context.
 * This interface provides methods to support a simple key/value
 * data structure.
 */
interface ProjectionContextMetadataInterface
{
    /**
     * Sets the value of a given key.
     */
    public function set(string $key, $value): void;

    /**
     * Returns the value of a given key, or a default value.
     *
     * @return mixed
     */
    public function get(string $key, $defaultValue = null);
}
