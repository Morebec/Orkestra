<?php

namespace Morebec\Orkestra\Worker;

/**
 * Implementation of a worker accepting a callable to be executed periodically.
 */
class CallableWorker extends AbstractWorker
{
    /**
     * @var callable
     */
    private $callable;

    public function __construct(WorkerOptions $options, callable $callable, iterable $watchers = [])
    {
        parent::__construct($options, $watchers);
        $this->callable = $callable;
    }

    protected function executeTask(): void
    {
        ($this->callable)();
    }
}
