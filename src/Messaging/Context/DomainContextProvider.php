<?php

namespace Morebec\Orkestra\Messaging\Context;

/**
 * The Domain Context Provider is responsible for providing the current Domain Message Bus Context.
 */
class DomainContextProvider implements DomainContextProviderInterface
{
    /**
     * @var DomainContextManagerInterface
     */
    private $contextManager;

    public function __construct(DomainContextManagerInterface $contextManager)
    {
        $this->contextManager = $contextManager;
    }

    /**
     * Returns the current Domain Message Bus Context or nullif there is none.
     */
    public function getContext(): ?DomainContext
    {
        return $this->contextManager->getContext();
    }

    /**
     * Method indicating if there is a correlation context at the moment.
     */
    public function hasContext(): bool
    {
        return $this->getContext() !== null;
    }
}
