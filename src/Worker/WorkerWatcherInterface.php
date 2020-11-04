<?php

namespace Morebec\Orkestra\Worker;

/**
 * Worker Watchers are capable of hooking into the lifecycle of workers
 * to perform various tasks.
 */
interface WorkerWatcherInterface
{
    /**
     * Called when the worker boots.
     */
    public function onBoot(WorkerInterface $worker): void;

    /**
     * Called periodically every time the worker runs a cycle.
     */
    public function onRun(WorkerInterface $worker): void;

    /**
     * Called when a worker shuts down.
     */
    public function onShutdown(WorkerInterface $worker): void;

    /**
     * Called when a worker was explicitly forced to stop.
     */
    public function onStop(WorkerInterface $worker): void;
}
