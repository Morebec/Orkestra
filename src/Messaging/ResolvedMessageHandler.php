<?php

namespace Morebec\Orkestra\Messaging;

/**
 * Represents a resolved message handler for a specific message.
 * A Resolved message handler corresponds to a message handler instance along with a method to call
 * to handle a specific message. It serves as a wrapper around and handler to easily call the right method.
 */
class ResolvedMessageHandler
{
    /**
     * @var object
     */
    private $instance;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $messageClass;

    public function __construct(
        string $messageClass,
        object $instance,
        string $method
    ) {
        $this->messageClass = $messageClass;
        $this->instance = $instance;
        $this->method = $method;
    }

    /**
     * Invokes the callable message handler with its method
     * and returns its optional result.
     *
     * @return mixed
     */
    public function invoke(object $message)
    {
        return $this->instance->{$this->method}($message);
    }

    /**
     * Returns the instance of the resolved message handler.
     */
    public function getInstance(): object
    {
        return $this->instance;
    }

    /**
     * Returns the method name of the callable message handler.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Returns the FQN of the class.
     */
    public function getClass(): string
    {
        return \get_class($this->instance);
    }

    /**
     * Returns the FQN of the class.
     */
    public function getMessageClass(): string
    {
        return $this->messageClass;
    }
}
