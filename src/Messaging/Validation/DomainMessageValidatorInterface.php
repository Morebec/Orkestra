<?php

namespace Morebec\Orkestra\Messaging\Validation;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * Represents services responsible for Validating Domain Messages before they are handled.
 */
interface DomainMessageValidatorInterface
{
    /**
     * Validates a {@link DomainMessageInterface} with given {@link DomainMessageHeaders}.
     */
    public function validate(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers): DomainMessageValidationErrorList;

    /**
     * Indicates if this Validator can validate a given {@link DomainMessageInterface} with  {@link DomainMessageHeaders}.
     */
    public function supports(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers): bool;
}
