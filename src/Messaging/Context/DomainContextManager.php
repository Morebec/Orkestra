<?php

namespace Morebec\Orkestra\Messaging\Context;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * The Domain Context Manager is responsible for managing the current message bus context.
 * It should be used solely by the responsible middleware of the {@link DomainMessageBusInterface},
 * namely the {@link BuildDomainContextMiddleware}.
 */
class DomainContextManager implements DomainContextManagerInterface
{
    /**
     * @var DomainContextStack
     */
    private $contextStack;

    public function __construct(?DomainContext $previousContext = null)
    {
        $this->contextStack = new DomainContextStack();
        if ($previousContext) {
            $this->contextStack->push($previousContext);
        }
    }

    /**
     * Starts a new context for a given message with headers.
     */
    public function startContext(DomainMessageInterface $message, DomainMessageHeaders $headers): void
    {
        $currentContext = $this->contextStack->peek();
        if ($currentContext) {
            $headers->set(DomainMessageHeaders::CORRELATION_ID, $currentContext->getCorrelationId());
            $headers->set(DomainMessageHeaders::CAUSATION_ID, $currentContext->getMessageId());
        } else {
            if (!$headers->get(DomainMessageHeaders::CORRELATION_ID)) {
                $headers->set(DomainMessageHeaders::CORRELATION_ID, $headers->get(DomainMessageHeaders::MESSAGE_ID));
            }

            if (!$headers->get(DomainMessageHeaders::CAUSATION_ID)) {
                $headers->set(DomainMessageHeaders::CAUSATION_ID, null);
            }
        }

        $this->contextStack->push(new DomainContext($message, $headers));
    }

    /**
     * Ends the currently active context or throw an exception if there was no started context.
     */
    public function endContext(): void
    {
        if (!$this->contextStack->peek()) {
            throw new NoDomainMessageBusContextToEndException();
        }

        $this->contextStack->pop();
    }

    /**
     * Returns the current context or null if there is none.
     */
    public function getContext(): ?DomainContext
    {
        return $this->contextStack->peek();
    }
}
