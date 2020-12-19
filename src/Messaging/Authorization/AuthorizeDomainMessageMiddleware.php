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
     * @var AuthorizationDecisionMakerInterface
     */
    private $decisionMaker;

    public function __construct(AuthorizationDecisionMakerInterface $decisionMaker)
    {
        $this->decisionMaker = $decisionMaker;
    }

    public function handle(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers, callable $next): DomainResponseInterface
    {
        if ($this->decisionMaker->supports($domainMessage, $headers)) {
            try {
                $this->decisionMaker->authorize($domainMessage, $headers);
            } catch (UnauthorizedException $e) {
                return new UnauthorizedDomainResponse($e);
            }
        }

        return $next($domainMessage, $headers);
    }
}
