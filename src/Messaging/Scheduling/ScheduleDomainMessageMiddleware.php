<?php

namespace Morebec\Orkestra\Messaging\Scheduling;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\DomainResponseInterface;
use Morebec\Orkestra\Messaging\DomainResponseStatusCode;
use Morebec\Orkestra\Messaging\Middleware\DomainMessageBusMiddlewareInterface;

/**
 * Middleware used to schedule a message instead of sending it for synchronous processing
 * through the use of headers.
 */
class ScheduleDomainMessageMiddleware implements DomainMessageBusMiddlewareInterface
{
    /**
     * @var DomainMessageSchedulerInterface
     */
    private $scheduler;

    public function __construct(DomainMessageSchedulerInterface $scheduler)
    {
        $this->scheduler = $scheduler;
    }

    public function handle(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers, callable $next): DomainResponseInterface
    {
        if (!$headers->get(DomainMessageHeaders::SCHEDULED_AT)) {
            return $next($domainMessage, $headers);
        }

        $this->scheduler->schedule($domainMessage, $headers);

        return new DomainMessageSchedulerResponse(DomainResponseStatusCode::ACCEPTED());
    }
}
