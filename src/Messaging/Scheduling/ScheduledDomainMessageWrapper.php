<?php

namespace Morebec\Orkestra\Messaging\Scheduling;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * Wrapper around domain messages that were scheduled.
 */
class ScheduledDomainMessageWrapper
{
    /**
     * @var DomainMessageInterface
     */
    private $message;
    /**
     * @var DomainMessageHeaders
     */
    private $headers;

    private function __construct(DomainMessageInterface $message, DomainMessageHeaders $headers)
    {
        $this->message = $message;
        $this->headers = $headers;
    }

    /**
     * @return static
     */
    public static function wrap(DomainMessageInterface $message, DomainMessageHeaders $headers): self
    {
        return new self($message, $headers);
    }

    public function getMessage(): DomainMessageInterface
    {
        return $this->message;
    }

    public function getMessageHeaders(): DomainMessageHeaders
    {
        return $this->headers;
    }

    /**
     * Returns the ID of the message.
     */
    public function getMessageId(): string
    {
        return $this->headers->get(DomainMessageHeaders::MESSAGE_ID);
    }
}
