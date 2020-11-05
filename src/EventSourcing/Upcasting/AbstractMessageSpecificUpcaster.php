<?php

namespace Morebec\Orkestra\EventSourcing\Upcasting;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;

/**
 * Abstract Implementation of an Upcaster for a specific Message type.
 * It defined the supports method to check that a message is of a specific type.
 */
abstract class AbstractMessageSpecificUpcaster implements UpcasterInterface
{
    /**
     * @var string
     */
    protected $messageType;

    public function __construct(string $messageType)
    {
        $this->messageType = $messageType;
    }

    public function supports(UpcastableMessage $message): bool
    {
        return $this->messageType === $message->metadata[DomainMessageHeaders::MESSAGE_TYPE];
    }
}
