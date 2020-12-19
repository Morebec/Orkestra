<?php

namespace Morebec\Orkestra\Messaging\Authorization;

use Morebec\Orkestra\Messaging\DomainMessageBusInterface;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;

/**
 * Service responsible for the Authorization of the handling of a {@link DomainMessageInterface}.
 */
interface DomainMessageAuthorizerInterface
{
    /**
     * Authorizes a {@link DomainMessageInterface} that was sent on the {@link DomainMessageBusInterface} with some given
     * {@link DomainMessageHeaders}.
     * If the message is authorized, simply returns silently. If the access is denied will throw an {@link UnauthorizedException}.
     *
     * @throws UnauthorizedException
     */
    public function authorize(DomainMessageInterface $domainMessage, DomainMessageHeaders $domainMessageHeaders): void;

    /**
     * Indicates if this {@link DomainMessageAuthorizerInterface} is able to authorize a specific {@link DomainMessageInterface}
     * with some given {@link DomainMessageHeaders} or not.
     */
    public function supports(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers): bool;
}
