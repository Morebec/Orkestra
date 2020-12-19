<?php

namespace Morebec\Orkestra\Messaging\Authorization;

use Morebec\Orkestra\Messaging\DomainResponseInterface;
use Morebec\Orkestra\Messaging\DomainResponseStatusCode;

class UnauthorizedDomainResponse implements DomainResponseInterface
{
    /**
     * @var UnauthorizedException
     */
    private $exception;

    public function __construct(UnauthorizedException $exception)
    {
        $this->exception = $exception;
    }

    public function getPayload()
    {
        return $this->exception;
    }

    public function isSuccess(): bool
    {
        return false;
    }

    public function isFailure(): bool
    {
        return true;
    }

    public function getStatusCode(): DomainResponseStatusCode
    {
        return DomainResponseStatusCode::REFUSED();
    }
}
