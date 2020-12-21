<?php

namespace Morebec\Orkestra\Messaging\Validation;

use Morebec\Orkestra\Messaging\DomainResponseInterface;
use Morebec\Orkestra\Messaging\DomainResponseStatusCode;

/**
 * Response returned when a Domain Message was deemed Invalid.
 */
class InvalidDomainMessageResponse implements DomainResponseInterface
{
    /**
     * @var DomainMessageValidationErrorList
     */
    private $errors;

    public function __construct(DomainMessageValidationErrorList $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return DomainMessageValidationErrorList
     */
    public function getPayload()
    {
        return $this->errors;
    }

    public function isSuccess(): bool
    {
        return false;
    }

    public function isFailure(): bool
    {
        return true;
    }

    public function getStatusCode(): DomainResponseStatusCode
    {
        return DomainResponseStatusCode::INVALID();
    }
}
