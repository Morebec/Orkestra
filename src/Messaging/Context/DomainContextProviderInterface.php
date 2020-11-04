<?php

namespace Morebec\Orkestra\Messaging\Context;

/**
 * The Domain Context Provider is responsible for providing the current Domain Message Bus Context.
 * It can be accessed in services depending on the context such as Message Handlers.
 */
interface DomainContextProviderInterface
{
    /**
     * Returns the current Domain Message Bus Context or nullif there is none.
     */
    public function getContext(): ?DomainContext;

    /**
     * Method indicating if there is a Domain Message Bus Context at the moment.
     */
    public function hasContext(): bool;
}
