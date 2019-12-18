<?php


namespace Morebec\Orkestra\Core\Util\Validation;

/**
 * Represents the result of a validation process
 */
class ValidationResult
{
    public $errors;

    public function __construct()
    {
        $this->errors = [];
    }

    /**
     * Indicates if there were errors or not during the validation process
     * @return bool true if there are errors, otherwise false
     */
    public function hasError(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Adds a new error message to this result
     * @param string $message
     */
    public function addError(string $message)
    {
        $this->errors = new ValidationError($message);
    }

    /**
     * Returns a list of all error messages
     * @return string[]
     */
    public function getErrorMessages(): array
    {
        return array_map(static function (ValidationError $e) {
            return $e->getMessage();
        }, $this->errors);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
