<?php

namespace Morebec\Orkestra\Messaging;

class DomainMessageHandlerResponse extends AbstractDomainResponse
{
    /**
     * @var string
     */
    protected $handlerName;

    public function __construct(string $handlerName, DomainResponseStatusCode $statusCode, $payload = null)
    {
        parent::__construct($statusCode, $payload);
        $this->handlerName = $handlerName;
    }

    public function getHandlerName(): string
    {
        return $this->handlerName;
    }
}
