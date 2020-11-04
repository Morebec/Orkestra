<?php

namespace Morebec\Orkestra\Messaging\Context;

use Morebec\Orkestra\DateTime\DateTime;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * Represents the current execution context of the domain.
 * It allows to track the correlation and causation of messages as well as any other message metadata.
 * It can be accessed with the {@link DomainContextProvider}.
 */
class DomainContext
{
    /**
     * @var DomainMessageInterface
     */
    private $message;
    /**
     * @var DomainMessageHeaders
     */
    private $messageHeaders;

    public function __construct(
        DomainMessageInterface $message,
        DomainMessageHeaders $messageHeaders
    ) {
        $this->message = $message;
        $this->messageHeaders = $messageHeaders;
    }

    public function getMessage(): DomainMessageInterface
    {
        return $this->message;
    }

    public function getMessageHeaders(): DomainMessageHeaders
    {
        return $this->messageHeaders;
    }

    /**
     * Returns the ID of the message.
     */
    public function getMessageId(): string
    {
        return $this->messageHeaders->get(DomainMessageHeaders::MESSAGE_ID);
    }

    /**
     * Returns the DateTime at which a message was sent to the bus.
     */
    public function getMessageSentAt(): DateTime
    {
        return DateTime::createFromFormat('U.u', (string) $this->messageHeaders->get(DomainMessageHeaders::SENT_AT));
    }

    /**
     * Returns the type of the message/.
     */
    public function getMessageType(): string
    {
        return $this->messageHeaders->get(DomainMessageHeaders::MESSAGE_TYPE_NAME);
    }

    /**
     * Returns the CorrelationId of the message.
     */
    public function getCorrelationId(): string
    {
        return $this->messageHeaders->get(DomainMessageHeaders::CORRELATION_ID);
    }

    /**
     * Returns the causation ID of the message.
     */
    public function getCausationId(): ?string
    {
        return $this->messageHeaders->get(DomainMessageHeaders::CAUSATION_ID);
    }
}
