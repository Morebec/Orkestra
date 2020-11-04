<?php

namespace Morebec\Orkestra\Messaging\Context;

/**
 * THe Domain Context Stack is used to allow nested domain contexts for message handlers calling other message handlers
 * synchronously as part of their work.
 */
class DomainContextStack
{
    /**
     * @var DomainContext[]
     */
    private $data;

    public function __construct()
    {
        $this->data = [];
    }

    /**
     * Pushes a domain context on top of this stack.
     */
    public function push(DomainContext $context): void
    {
        $this->data[] = $context;
    }

    /**
     * Removes the element on the top of the stack.
     */
    public function pop(): DomainContext
    {
        $ctx = array_pop($this->data);
        if (!$ctx) {
            throw new \LogicException('Cannot pop Domain Context Stack: No context left on stack');
        }

        return $ctx;
    }

    /**
     * Returns the element at the top of the stack.
     */
    public function peek(): ?DomainContext
    {
        $nbContexts = \count($this->data);

        if ($nbContexts === 0) {
            return null;
        }

        return $this->data[$nbContexts - 1];
    }
}
