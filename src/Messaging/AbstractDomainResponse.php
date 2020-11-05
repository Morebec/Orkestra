<?php

namespace Morebec\Orkestra\Messaging;

/**
 * Abstract implementation of a Domain Response implementing
 * the isSuccess and isFailure methods according to the provided status code.
 */
class AbstractDomainResponse implements DomainResponseInterface
{
    /**
     * @var DomainResponseStatusCode
     */
    protected $statusCode;

    /**
     * @var mixed
     */
    protected $payload;

    public function __construct(
        DomainResponseStatusCode $statusCode,
        $payload = null
    ) {
        $this->statusCode = $statusCode;
        $this->payload = $payload;
    }

    public function getStatusCode(): DomainResponseStatusCode
    {
        return $this->statusCode;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function isFailure(): bool
    {
        return $this->statusCode->isEqualTo(DomainResponseStatusCode::FAILED()) ||
            $this->statusCode->isEqualTo(DomainResponseStatusCode::REFUSED()) ||
            $this->statusCode->isEqualTo(DomainResponseStatusCode::INVALID());
    }

    public function isSuccess(): bool
    {
        return !$this->isFailure();
    }
}
