<?php

namespace Morebec\Orkestra\EventSourcing\Upcasting;

/**
 * Implementation of an upcaster that does a one-to-one mapping between a specific message at version x and version x+1.
 */
abstract class AbstractSingleMessageUpcaster extends AbstractMessageSpecificUpcaster implements UpcasterInterface
{
    public function upcast(UpcastableMessage $message): array
    {
        return [$this->doUpcast($message)];
    }

    /**
     * Helper method of the SingleMessage Upcaster allowing to return a single message.
     */
    abstract protected function doUpcast(UpcastableMessage $message): UpcastableMessage;
}
