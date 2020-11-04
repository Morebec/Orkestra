<?php

namespace Morebec\Orkestra\EventSourcing\Upcasting;

/**
 * Typed implementation of a message that can be upcasted.
 */
class UpcastableMessage
{
    /**
     * @var array
     */
    public $data;

    /**
     * @var array
     */
    public $metadata;

    public function __construct(array $data, array $metadata = [])
    {
        $this->data = $data;
        $this->metadata = $metadata;
    }

    /**
     * Adds a new field to this message's data.
     *
     * @param null $defaultValue
     */
    public function addField(string $fieldName, $defaultValue = null): void
    {
        $this->data[$fieldName] = $defaultValue;
    }

    /**
     * Renames a field in the data.
     */
    public function renameField(string $fieldName, string $newFieldName): void
    {
        $this->addField($newFieldName, $this->data[$fieldName]);
        $this->removeField($fieldName);
    }

    /**
     * Removes a field from this message's data.
     */
    public function removeField(string $fieldName): void
    {
        unset($this->data[$fieldName]);
    }
}
