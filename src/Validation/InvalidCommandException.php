<?php

namespace Morebec\Orkestra\Validation;

use Exception;
use Morebec\Orkestra\Messaging\Command\CommandInterface;
use Morebec\Validator\ValidationErrorList;
use Throwable;

/**
 * Exception thrown when the validation of a command fails.
 */
class InvalidCommandException extends Exception
{
    /**
     * @var CommandInterface
     */
    private $command;
    /**
     * @var ValidationErrorList
     */
    private $errors;

    public function __construct(
        CommandInterface $command,
        ValidationErrorList $errors,
        string $message = null,
        $code = 0,
        Throwable $previous = null
    ) {
        $this->command = $command;
        $this->errors = $errors;

        if (!$message) {
            $message = $this->getCommandName().' was invalid';
        }

        parent::__construct($message, $code, $previous);
    }

    public function getCommandName(): string
    {
        return \get_class($this->command);
    }

    public function getCommand(): CommandInterface
    {
        return $this->command;
    }

    public function getErrors(): ValidationErrorList
    {
        return $this->errors;
    }
}
