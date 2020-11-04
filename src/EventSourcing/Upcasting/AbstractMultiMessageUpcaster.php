<?php

namespace Morebec\Orkestra\EventSourcing\Upcasting;

/**
 * Implementation of an Upcaster that is specifically designed to allow demultiplexing.
 * It follows an API similar to the {@link AbstractSingleMessageUpcaster} by allowing the implementor
 * to implement a `doUpcast` method.
 */
abstract class AbstractMultiMessageUpcaster extends AbstractMessageSpecificUpcaster implements UpcasterInterface
{
    public function upcast(UpcastableMessage $message): array
    {
        return $this->doUpcast($message);
    }

    /**
     * Helper method of the AbstractMultiMessageUpcaster allowing to return a multiple upcastable messages.
     *
     * @return UpcastableMessage[]
     */
    abstract protected function doUpcast(UpcastableMessage $message): array;
}
