<?php

namespace Morebec\Orkestra\Modeling;

use Morebec\ValueObjects\Identity\UuidIdentifier;
use Morebec\ValueObjects\StringBasedValueObject;

abstract class AbstractEntityIdentifier extends StringBasedValueObject implements EntityIdentifierInterface
{
    /**
     * Creates an instance using a value as a string.
     *
     * @return static
     */
    public static function fromString(string $value): self
    {
        return new static($value);
    }

    /**
     * Generates a UUID for this identifier.
     *
     * @return static
     */
    public static function generate(): self
    {
        return self::fromString(UuidIdentifier::generate());
    }
}
