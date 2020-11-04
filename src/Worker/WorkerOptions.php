<?php

namespace Morebec\Orkestra\Worker;

/**
 * Represents the options of a Worker.
 */
class WorkerOptions
{
    /** @var int number of milliseconds that a Worker has to wait before running again. */
    public $sleepInterval = 1;

    /** @var int number of *seconds* that a Worker can be running for before shutting down. */
    public $maxExecutionTime = 3600;
}
