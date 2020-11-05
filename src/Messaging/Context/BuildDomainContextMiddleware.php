<?php

namespace Morebec\Orkestra\Messaging\Context;

use Morebec\Orkestra\DateTime\ClockInterface;
use Morebec\Orkestra\Messaging\Command\DomainCommandInterface;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\DomainResponseInterface;
use Morebec\Orkestra\Messaging\Event\DomainEventInterface;
use Morebec\Orkestra\Messaging\Middleware\DomainMessageBusMiddlewareInterface;
use Morebec\Orkestra\Messaging\Query\DomainQueryInterface;
use Ramsey\Uuid\Uuid;

/**
 * Middleware responsible for building the domain context.
 */
class BuildDomainContextMiddleware implements DomainMessageBusMiddlewareInterface
{
    /**
     * @var ClockInterface
     */
    private $clock;

    /**
     * @var DomainContextManagerInterface
     */
    private $contextManager;

    public function __construct(ClockInterface $clock, DomainContextManagerInterface $contextManager)
    {
        $this->clock = $clock;
        $this->contextManager = $contextManager;
    }

    public function handle(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers, callable $next): DomainResponseInterface
    {
        // Build domain context
        $headers->set(DomainMessageHeaders::MESSAGE_TYPE_NAME, $domainMessage::getTypeName());

        $headers->set(DomainMessageHeaders::MESSAGE_TYPE, $this->detectMessageType($domainMessage));

        $headers->set(DomainMessageHeaders::SENT_AT, $this->clock->now()->getMillisTimestamp());

        if (!$headers->get(DomainMessageHeaders::MESSAGE_ID)) {
            $headers->set(DomainMessageHeaders::MESSAGE_ID, (string) Uuid::uuid4());
        }

        $this->contextManager->startContext($domainMessage, $headers);

        $response = $next($domainMessage, $headers);

        // Unset domain context
        $this->contextManager->endContext();

        return $response;
    }

    /**
     * Detects the type of a message and returns it as a string.
     * (E.g. event, command, query). For non standard messages returns "generic".
     */
    protected function detectMessageType(DomainMessageInterface $domainMessage): string
    {
        if ($domainMessage instanceof DomainEventInterface) {
            $messageType = 'event';
        } elseif ($domainMessage instanceof DomainCommandInterface) {
            $messageType = 'command';
        } elseif ($domainMessage instanceof DomainQueryInterface) {
            $messageType = 'query';
        } else {
            $messageType = 'generic';
        }

        return $messageType;
    }
}
