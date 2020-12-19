<?php

namespace Morebec\Orkestra\Messaging\Authorization;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * Concrete implementation of {@link AuthorizationDecisionMakerInterface} that grants the handling
 * of a Domain Message as soon as any of its inner {@link DomainMessageAuthorizer} denies access ot the handling.
 */
class VetoAuthorizationDecisionMaker implements AuthorizationDecisionMakerInterface
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
        foreach ($this->authorizers as $authorizer) {
            if (!$authorizer->supports($domainMessage, $headers)) {
                continue;
            }

            $authorizer->authorize($domainMessage, $headers);
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
