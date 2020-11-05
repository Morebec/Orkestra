<?php

namespace Morebec\Orkestra\Worker;

/**
 * A Worker is a service capable of running a task continuously at a specified interval.
 */
abstract class AbstractWorker implements WorkerInterface
{
    private const MICRO_SECONDS_TO_MILLISECONDS = 1000;

    /**
     * @var WorkerOptions
     */
    protected $options;

    /**
     * @var bool
     */
    protected $booted;

    /**
     * @var WorkerWatcherInterface[]
     */
    private $watchers;

    /**
     * Indicates if the worker should be stopped at any given point.
     *
     * @var bool
     */
    private $stopped;

    /** @var int timestamp at which this worker has been started. */
    private $startedAt;

    public function __construct(WorkerOptions $options, iterable $watchers = [])
    {
        $this->options = $options;
        $this->watchers = $watchers;
        $this->stopped = false;
    }

    /**
     * Boots the worker so it can initialize itself. This should always be called first before calling run.
     */
    public function boot(): void
    {
        $this->booted = true;
        $this->startedAt = time();

        foreach ($this->watchers as $watcher) {
            $watcher->onBoot($this);
        }
    }

    /**
     * Runs the task of the worker.
     */
    public function run(): void
    {
        while (!$this->mustStop()) {
            foreach ($this->watchers as $watcher) {
                $watcher->onRun($this);
            }
            $this->executeTask();
            usleep($this->options->sleepInterval * self::MICRO_SECONDS_TO_MILLISECONDS);
        }

        $this->shutdown();
    }

    public function stop(): void
    {
        $this->stopped = true;
        foreach ($this->watchers as $watcher) {
            $watcher->onStop($this);
        }
    }

    /**
     * Shuts down the worker allowing it to do some clean up tasks before finishing.
     * Called automatically a\by the run method when it is done.
     */
    public function shutdown(): void
    {
        foreach ($this->watchers as $watcher) {
            $watcher->onShutdown($this);
        }
    }

    /**
     * Conditional check done during at every run cycle deciding whether or not this Worker should stop running.
     */
    public function mustStop(): bool
    {
        $now = time();
        $timeRunning = $now - $this->startedAt;

        $maxExecutionTime = $this->options->maxExecutionTime;
        if ($maxExecutionTime !== 0 && $timeRunning > $maxExecutionTime) {
            return true;
        }

        return $this->stopped;
    }

    /**
     * Adds a Worker to the list of watchers.
     */
    public function addWatcher(WorkerWatcherInterface $watcher): void
    {
        $this->watchers[] = $watcher;
    }

    /**
     * Executes the actual task that the worker has to do on every run.
     */
    abstract protected function executeTask(): void;
}
