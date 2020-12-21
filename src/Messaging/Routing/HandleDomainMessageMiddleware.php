<?php

namespace Morebec\Orkestra\Messaging\Routing;

use LogicException;
use Morebec\Orkestra\Messaging\Command\DomainCommandHandlerInterface;
use Morebec\Orkestra\Messaging\Command\DomainCommandHandlerResponse;
use Morebec\Orkestra\Messaging\Command\DomainCommandInterface;
use Morebec\Orkestra\Messaging\DomainMessageHandlerInterface;
use Morebec\Orkestra\Messaging\DomainMessageHandlerResponse;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\DomainResponseInterface;
use Morebec\Orkestra\Messaging\DomainResponseStatusCode;
use Morebec\Orkestra\Messaging\Event\DomainEventHandlerInterface;
use Morebec\Orkestra\Messaging\Event\DomainEventHandlerResponse;
use Morebec\Orkestra\Messaging\Middleware\DomainMessageBusMiddlewareInterface;
use Morebec\Orkestra\Messaging\MultiDomainMessageHandlerResponse;
use Morebec\Orkestra\Messaging\Normalization\DomainMessageNormalizerInterface;
use Morebec\Orkestra\Messaging\Query\DomainQueryHandlerInterface;
use Morebec\Orkestra\Messaging\Query\DomainQueryHandlerResponse;
use Morebec\Orkestra\Messaging\Query\DomainQueryInterface;
use Morebec\Orkestra\Modeling\DomainExceptionInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * This middleware handles a domain message and forwards it to the handlers that should receive it,
 * by relying on the routes defined in the headers of a {@link DomainMessageInterface}.
 * It obtains instances of {@link DomainMessageInterface} through the {@link DomainMessageHandlerProviderInterface}.
 */
class HandleDomainMessageMiddleware implements DomainMessageBusMiddlewareInterface
{
    /**
     * @var DomainMessageHandlerProviderInterface
     */
    private $handlerProvider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DomainMessageNormalizerInterface
     */
    private $domainMessageNormalizer;

    public function __construct(
        DomainMessageHandlerProviderInterface $handlerProvider,
        DomainMessageNormalizerInterface $domainMessageNormalizer,
        LoggerInterface $logger
    ) {
        $this->handlerProvider = $handlerProvider;
        $this->logger = $logger;
        $this->domainMessageNormalizer = $domainMessageNormalizer;
    }

    public function handle(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers, callable $next): DomainResponseInterface
    {
        $routes = $headers->get(DomainMessageHeaders::DESTINATION_HANDLER_NAMES, []);

        $responses = [];
        /** @var string $route */
        foreach ($routes as $route) {
            [$handlerClassName, $handlerMethodName] = explode('::', $route);

            $handler = $this->handlerProvider->getDomainMessageHandler($handlerClassName);

            if (!$handler) {
                throw new LogicException(sprintf('Domain Message Handler "%s" was not found.', $handlerClassName));
            }

            $this->logger->info('Sending domain message of type "{messageType}" to message handler "{messageHandlerName}::{messageHandlerMethodName}".', [
                'messageType' => $domainMessage::getTypeName(),
                'messageHeaders' => $headers->toArray(),
                'message' => $this->domainMessageNormalizer->normalize($domainMessage),
                'messageHandlerName' => $handlerClassName,
                'messageHandlerMethodName' => $handlerMethodName,
            ]);

            // Invoke Handler
            try {
                $payload = $handler->{$handlerMethodName}($domainMessage);
            } catch (Throwable $throwable) {
                $payload = $throwable;
            }

            $response = $this->buildResponseForMessageHandlerPayload($handler, $payload);
            $responses[] = $response;

            $this->logger->info('Message of type "{messageType}" received by handler "{messageHandlerName}::{messageHandlerMethodName}".', [
                'messageType' => $domainMessage::getTypeName(),
                'messageHandlerName' => $handlerClassName,
                'messageHandlerMethodName' => $handlerMethodName,
                'responseStatusCode' => $response->getStatusCode(),
                'responseFailed' => $response->isFailure(),
            ]);
        }

        $finalResponse = $this->buildResponse($responses);

        if ($finalResponse instanceof UnhandledDomainMessageResponse) {
            if ($domainMessage instanceof DomainCommandInterface || $domainMessage instanceof DomainQueryInterface) {
                throw new UnhandledMessageException($domainMessage);
            }

            $this->logger->warning('Message of type "{messageType}" was not handled.', [
                'messageType' => $domainMessage::getTypeName(),
            ]);
        }

        return $finalResponse;
    }

    /**
     * Builds a sensible response for a handler invocation according to the returned payload.
     *
     * @param $payload
     */
    protected function buildResponseForMessageHandlerPayload(DomainMessageHandlerInterface $messageHandler, $payload): DomainResponseInterface
    {
        if ($payload instanceof DomainResponseInterface) {
            return $payload;
        }

        if ($payload instanceof DomainResponseStatusCode) {
            $statusCode = $payload;
            $payload = null;
        } else {
            if ($payload instanceof Throwable) {
                if ($payload instanceof DomainExceptionInterface) {
                    $statusCode = DomainResponseStatusCode::REFUSED();
                } else {
                    $statusCode = DomainResponseStatusCode::FAILED();
                }
            } else {
                $statusCode = DomainResponseStatusCode::SUCCEEDED();
            }
        }

        $messageHandlerName = \get_class($messageHandler);

        if ($messageHandler instanceof DomainCommandHandlerInterface) {
            return new DomainCommandHandlerResponse($messageHandlerName, $statusCode, $payload);
        }

        if ($messageHandler instanceof DomainEventHandlerInterface) {
            return new DomainEventHandlerResponse($messageHandlerName, $statusCode, $payload);
        }

        if ($messageHandler instanceof DomainQueryHandlerInterface) {
            return new DomainQueryHandlerResponse($messageHandlerName, $statusCode, $payload);
        }

        return new DomainMessageHandlerResponse($messageHandlerName, $statusCode, $payload);
    }

    /**
     * Returns a response using the list of responses for all the handlers.
     */
    protected function buildResponse(array $responses): DomainResponseInterface
    {
        // Determine the type of response we must provide.
        $nbResponses = \count($responses);

        if ($nbResponses === 0) {
            return new UnhandledDomainMessageResponse();
        }

        if ($nbResponses === 1) {
            return $responses[0];
        }

        return new MultiDomainMessageHandlerResponse($responses);
    }
}
