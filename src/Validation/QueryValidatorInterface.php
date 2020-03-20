<?php

namespace Morebec\Orkestra\Validation;

use Morebec\Orkestra\Messaging\Query\QueryInterface;

/**
 * Interface QueryValidatorInterface
 * Validator interface for validating queries in query handlers.
 */
interface QueryValidatorInterface
{
    /**
     * Validates a query.
     * Throws an exception if the query is invalid.
     */
    public static function validate(QueryInterface $query): void;
}
