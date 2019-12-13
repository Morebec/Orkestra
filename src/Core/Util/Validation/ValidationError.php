<?php


namespace Morebec\Orkestra\Core\Util\Validation;

/**
 * Represents an error during a validation process
 */
class ValidationError
{
    /**
     * @var string
     */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }
    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}