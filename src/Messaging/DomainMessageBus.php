<?php

namespace Morebec\Orkestra\Messaging;

use Morebec\Orkestra\Messaging\Middleware\DomainMessageBusMiddlewareInterface;

class DomainMessageBus implements DomainMessageBusInterface
{
    /**
     * @var DomainMessageBusMiddlewareInterface[]
     */
    private $middleware;

    public function __construct(iterable $middleware = [])
    {
        $this->middleware = [];
        foreach ($middleware as $m) {
            $this->appendMiddleware($m);
        }
    }

    public function sendMessage(DomainMessageInterface $domainMessage, ?DomainMessageHeaders $headers = null): DomainResponseInterface
    {
        $next = $this->createCallableForNextMiddleware(0);

        if (!$headers) {
            $headers = new DomainMessageHeaders();
        }

        return $next($domainMessage, $headers);
    }

    public function appendMiddleware(DomainMessageBusMiddlewareInterface $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function prependMiddleware(DomainMessageBusMiddlewareInterface $middleware): void
    {
        array_unshift($this->middleware, $middleware);
    }

    /**
     * Creates a callable for a middleware at a given index. (the $next parameter).
     */
    private function createCallableForNextMiddleware(int $currentMiddlewareIndex): callable
    {
        // If we are past all the middleware, throw a default response, this would mean that no middleware decided to return a response.
        if (!\array_key_exists($currentMiddlewareIndex, $this->middleware)) {
            return static function (DomainMessageInterface $domainMessage): DomainResponseInterface {
                throw new NoResponseFromMiddlewareException($domainMessage);
            };
        }

        $middleware = $this->middleware[$currentMiddlewareIndex];

        $self = $this;

        return function (DomainMessageInterface $message, DomainMessageHeaders $headers) use ($self, $currentMiddlewareIndex, $middleware): DomainResponseInterface {
            return $middleware->handle(
                $message,
                $headers,
                $self->createCallableForNextMiddleware($currentMiddlewareIndex + 1)
            );
        };
    }
}
