<?php

namespace Morebec\Orkestra\Messaging;

/**
 * Response representing the fact that multiple message handlers returned a response for a given domain message.
 */
class MultiDomainMessageHandlerResponse extends AbstractDomainResponse
{
    /**
     * @var DomainMessageHandlerResponse[]
     */
    private $handlerResponses;

    public function __construct(iterable $handlerResponses)
    {
        $this->handlerResponses = [];
        foreach ($handlerResponses as $handlerResponse) {
            $this->handlerResponses[] = $handlerResponse;
        }

        if (empty($this->handlerResponses)) {
            throw new \InvalidArgumentException('A MultiDomainMessageHandlerResponse cannot receive an empty array of responses');
        }

        if (\count($this->handlerResponses) === 1) {
            throw new \InvalidArgumentException('A MultiDomainMessageHandlerResponse must receive an array of responses of a length greater than 1');
        }

        // Determine a Status Code
        $hasFailed = $this->hasResponseWithStatus(DomainResponseStatusCode::FAILED()) ||
            $this->hasResponseWithStatus(DomainResponseStatusCode::INVALID()) ||
            $this->hasResponseWithStatus(DomainResponseStatusCode::REFUSED())
        ;

        $statusCode = $hasFailed ? DomainResponseStatusCode::FAILED() : DomainResponseStatusCode::SUCCEEDED();

        // Determine the payloads of this response.
        $payloads = [];
        foreach ($this->handlerResponses as $handlerResponse) {
            $payloads[$handlerResponse->getHandlerName()] = $handlerResponse->getPayload();
        }

        parent::__construct($statusCode, $payloads);
    }

    /**
     * Indicates if at least one response contained in this response has a given status code.
     */
    public function hasResponseWithStatus(DomainResponseStatusCode $statusCode): bool
    {
        foreach ($this->handlerResponses as $response) {
            if ($response->getStatusCode()->isEqualTo($statusCode)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return DomainMessageHandlerResponse[]
     */
    public function getHandlerResponses(): array
    {
        return $this->handlerResponses;
    }
}
