<?php

namespace Morebec\Orkestra\Messaging\Authorization;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * Concrete implementation of {@link AuthorizationDecisionMakerInterface} that grants the handling
 * of a Domain Message as soon as any of its inner {@link DomainMessageAuthorizer} grants access ot the handling.
 */
class AffirmativeAuthorizationDecisionMaker implements AuthorizationDecisionMakerInterface
{
    /**
     * @var DomainMessageAuthorizerInterface[]
     */
    private $authorizers;

    public function __construct(iterable $authorizers)
    {
        $this->authorizers = [];
        foreach ($authorizers as $authorizer) {
            $this->authorizers[] = $authorizer;
        }
    }

    public function authorize(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers): void
    {
        /** @var UnauthorizedException|null $exception */
        $exception = null;
        foreach ($this->authorizers as $authorizer) {
            if (!$authorizer->supports($domainMessage, $headers)) {
                continue;
            }

            try {
                $authorizer->authorize($domainMessage, $headers);
                break; // First Supported authorizer allows the process the request.
            } catch (UnauthorizedException $e) {
                $exception = $e;
            }
        }

        if ($exception) {
            throw $exception;
        }
    }

    public function supports(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers): bool
    {
        foreach ($this->authorizers as $authorizer) {
            if ($authorizer->supports($domainMessage, $headers)) {
                return true;
            }
        }

        return false;
    }
}
