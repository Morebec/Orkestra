<?php


namespace Morebec\Orkestra\Messaging\Query;

/**
 * Interface QueryHandlerInterface
 * A Query handler is responsible for handling a command that was dispatched through the query bus.
 * There should be a one-to-one relationship between a query and a query handler
 * To implement this interface, create an __invoke method taking a specific QueryInterface as a parameter.
 * @template T of QueryInterface
 */
interface QueryHandlerInterface
{
}
