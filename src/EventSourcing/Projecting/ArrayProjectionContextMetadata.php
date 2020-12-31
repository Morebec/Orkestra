<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

/**
 * Array based Projection Context Metadata.
 */
class ArrayProjectionContextMetadata implements ProjectionContextMetadataInterface
{
    /** @var array */
    private $data;

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function get(string $key, $defaultValue = null)
    {
        if (!\array_key_exists($key, $this->data)) {
            return $defaultValue;
        }

        return $this->data[$key];
    }
}
