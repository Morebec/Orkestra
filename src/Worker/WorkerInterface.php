<?php

namespace Morebec\Orkestra\Worker;

/**
 * A Worker is a service capable of running a task continuously at a specified interval.
 */
interface WorkerInterface
{
    /**
     * Runs the task of the worker.
     */
    public function run(): void;

    /**
     * Forces the Worker to stop. Calling this method should always have the effect of
     * stopping the worker and calling its shutdown method.
     */
    public function stop(): void;

    /**
     * Shuts down the worker allowing it to do some clean up tasks before finishing.
     * Called automatically a\by the run method when it is done.
     */
    public function shutdown(): void;

    /**
     * Boots the worker so it can initialize itself. This should always be called first before calling run.
     */
    public function boot(): void;
}
