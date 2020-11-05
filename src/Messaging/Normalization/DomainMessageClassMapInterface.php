<?php

namespace Morebec\Orkestra\Messaging\Normalization;

/**
 * In order to save events (in an event store for e.g.) or any other messages
 * (such as a command store or scheduled messages) and retain typing information without
 * requiring to save FQDNs (Which is complex to change whenever namespaces change for types)
 * a map of message type names and their actual types need to be created for deserialization purposes.
 * This interface provides the contract for such functionality.
 */
interface DomainMessageClassMapInterface
{
    /**
     * Adds a new mapping between a Domain Message with a given type name and its corresponding class name.
     */
    public function addMapping(string $domainMessageTypeName, string $domainMessageClassName): void;

    /**
     * Returns the class name associated to a Domain Message type.
     * If no mapping exists, returns null.
     */
    public function getClassNameForDomainMessageTypeName(string $domainMessageTypeName): ?string;

    /**
     * Returns an array representation of this class map, where keys are domain message type
     * names and values their associated class name.
     */
    public function toArray(): array;
}
