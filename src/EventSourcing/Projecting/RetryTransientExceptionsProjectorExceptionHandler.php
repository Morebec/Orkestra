<?php

namespace Morebec\Orkestra\EventSourcing\Projecting;

use Morebec\Orkestra\Modeling\TransientExceptionInterface;

/**
 * Implementation of a Projector Exception Handler that retries on TransientExceptions.
 */
class RetryTransientExceptionsProjectorExceptionHandler
{
    public function __invoke(ProjectionContextInterface $context, \Throwable $t, int $nbAttempts): bool
    {
        if (!($t instanceof TransientExceptionInterface)) {
            return false;
        }

        $oneMs = 10000;
        usleep(pow(200 * $oneMs, $nbAttempts));

        return $nbAttempts < 3;
    }
}
