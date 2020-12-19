<?php

namespace Morebec\Orkestra\Messaging\Authorization;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * Makes a final decision about whether or not some messages should be handled.
 * It relies on {@link DomainMessageAuthorizerInterface} and determines based on their answer
 * a final decision.
 */
interface AuthorizationDecisionMakerInterface extends DomainMessageAuthorizerInterface
{
    /**
     * Authorizes a {@link DomainMessageInterface} that was sent on the {@link DomainMessageBusInterface} with some given
     * {@link DomainMessageHeaders}.
     * If the message is authorized, simply returns silently.
     * If the access is denied will throw an {@link UnauthorizedException}.
     *
     * @throws UnauthorizedException
     */
    public function authorize(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers): void;
}
