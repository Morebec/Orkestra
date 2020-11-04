<?php

namespace Morebec\Orkestra\Validation;

use Exception;
use Morebec\Orkestra\Messaging\Query\QueryInterface;
use Morebec\Validator\ValidationErrorList;
use Throwable;

/**
 * Exception thrown when the validation of a query fails.
 */
class InvalidQueryException extends \RuntimeException
{
    /**
     * @var QueryInterface
     */
    private $query;
    /**
     * @var ValidationErrorList
     */
    private $errors;

    public function __construct(
        QueryInterface $query,
        ValidationErrorList $errors,
        string $message = null,
        $code = 0,
        Throwable $previous = null
    ) {
        $this->query = $query;
        $this->errors = $errors;

        if (!$message) {
            $message = $this->getQueryName().' was invalid';
        }

        parent::__construct($message, $code, $previous);
    }

    public function getQueryName(): string
    {
        return \get_class($this->query);
    }

    public function getQuery(): QueryInterface
    {
        return $this->query;
    }

    public function getErrors(): ValidationErrorList
    {
        return $this->errors;
    }
}
