<?php

namespace Morebec\Orkestra\Messaging\Authorization;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\DomainResponseInterface;
use Morebec\Orkestra\Messaging\Middleware\DomainMessageBusMiddlewareInterface;

/**
 * This middleware is responsible for authorizing domain messages.
 */
class AuthorizeDomainMessageMiddleware implements DomainMessageBusMiddlewareInterface
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

    public function handle(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers, callable $next): DomainResponseInterface
    {
        foreach ($this->authorizers as $authorizer) {
            if (!$authorizer->supports($domainMessage, $headers)) {
                continue;
            }

            try {
                $authorizer->authorize($domainMessage, $headers);
            } catch (UnauthorizedException $e) {
                return new UnauthorizedDomainResponse($e);
            }
        }

        return $next($domainMessage, $headers);
    }
}
