<?php


namespace Morebec\Orkestra\Messaging\Exception;

/**
 * Exception thrown when a change method expected a real change but the
 * value provided was the same one as the old one.
 */
class SameValueProvidedOnChangeMethodException extends \Exception
{
    public static function createForArgument(string $argumentName, string $value): self
    {
        return new static("Expected argument {$argumentName} to have a different value");
    }
}
