<?php


namespace Morebec\Orkestra\Messaging\Query;

/**
 * Interface QueryBusInterface.
 * The Query bus is responsible for dispatching a query to the right query handler and returning its
 * query result.
 */
interface QueryBusInterface
{
    /**
     * Dispatches a Query to the right Query handler
     * @param QueryInterface $query
     * @return mixed
     */
    public function dispatch(QueryInterface $query);
}
