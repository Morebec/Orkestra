<?php

namespace Morebec\Orkestra\EventSourcing\Upcasting;

/**
 * Abstract implementation of a Message Specific Upcaster that also checks for the version of the message
 * in its `supports` method. by checking for a field `version` in the data of the message.
 */
abstract class AbstractMessageVersionSpecificUpcaster extends AbstractMessageSpecificUpcaster
{
    /**
     * @var int
     */
    protected $messageVersion;

    public function __construct(string $messageType, int $messageVersion)
    {
        parent::__construct($messageType);
        $this->messageVersion = $messageVersion;
    }

    public function supports(UpcastableMessage $message): bool
    {
        if (!parent::supports($message)) {
            return false;
        }

        return $this->messageVersion === $message->data['version'];
    }
}
