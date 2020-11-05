<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

use Morebec\Orkestra\Worker\AbstractWorker;
use Morebec\Orkestra\Worker\WorkerOptions;

/**
 * Worker ensuring that the projectionist runs required projectors so they are always up to date.
 */
class ProjectionistWorker extends AbstractWorker
{
    /**
     * @var ProjectionistInterface
     */
    private $projectionist;

    /**
     * @var iterable
     */
    private $projectors;

    public function __construct(
        WorkerOptions $options,
        ProjectionistInterface $projectionist,
        iterable $projectors,
        iterable $watchers = []
    ) {
        parent::__construct($options, $watchers);
        $this->projectionist = $projectionist;
        $this->projectors = $projectors;
    }

    protected function executeTask(): void
    {
        foreach ($this->projectors as $projector) {
            $this->projectionist->runProjector($projector);
        }
    }
}
