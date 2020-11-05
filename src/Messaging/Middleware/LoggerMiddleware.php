<?php

namespace Morebec\Orkestra\Messaging\Middleware;

use Morebec\Orkestra\Messaging\DomainMessageHandlerResponse;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\DomainResponseInterface;
use Morebec\Orkestra\Messaging\DomainResponseStatusCode;
use Morebec\Orkestra\Messaging\MultiDomainMessageHandlerResponse;
use Morebec\Orkestra\Messaging\Normalization\DomainMessageNormalizerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Domain Message Bus middleware logging messages and responses.
 */
class LoggerMiddleware implements DomainMessageBusMiddlewareInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DomainMessageNormalizerInterface
     */
    private $objectNormalizer;

    public function __construct(LoggerInterface $logger, DomainMessageNormalizerInterface $objectNormalizer)
    {
        $this->logger = $logger;
        $this->objectNormalizer = $objectNormalizer;
    }

    public function handle(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers, callable $next): DomainResponseInterface
    {
        $messageContext = $this->buildMessageContext($domainMessage, $headers);
        $this->logger->info('Received message {messageTypeName}',
            $messageContext
        );

        /** @var DomainResponseInterface $response */
        $response = $next($domainMessage, $headers);

        $responseContext = $this->buildResponseContext($response);

        if ($response->getStatusCode()->isEqualTo(DomainResponseStatusCode::FAILED())) {
            if ($response instanceof DomainMessageHandlerResponse) {
                $this->logger->error('Message Handler "{messageHandler}" Failed for message of type - {messageTypeName} - "{exceptionMessage}".',
                $messageContext + $responseContext
                );
            } elseif ($response instanceof MultiDomainMessageHandlerResponse) {
                foreach ($response->getHandlerResponses() as $handlerResponse) {
                    $handlerResponseContext = $this->buildResponseContext($handlerResponse);
                    $this->logger->error('Message Handler "{messageHandler}" Failed for message of type - {messageTypeName} - "{exceptionMessage}".',
                        $handlerResponseContext
                    );
                }
            } else {
                $this->logger->error('Failed to process message of type - {messageTypeName} - "{exceptionMessage}".',
                    $messageContext + $responseContext
                );
            }
        } else {
            $this->logger->info('Received response {responseStatusCode} for message of type - {messageTypeName}.',
                $messageContext + $responseContext
            );
        }

        return $response;
    }

    /**
     * Builds the logging context for message and header.
     */
    private function buildMessageContext(DomainMessageInterface $message, DomainMessageHeaders $headers): array
    {
        return [
            'messageTypeName' => $headers->get(DomainMessageHeaders::MESSAGE_TYPE_NAME),
            'messageType' => $headers->get(DomainMessageHeaders::MESSAGE_TYPE),
            'message' => $this->objectNormalizer->normalize($message),
            'messageHeaders' => $headers->toArray(),
            'messageId' => $headers->get(DomainMessageHeaders::MESSAGE_ID),
            'causationId' => $headers->get(DomainMessageHeaders::CAUSATION_ID),
            'correlationId' => $headers->get(DomainMessageHeaders::CORRELATION_ID),
        ];
    }

    /**
     * Builds the logging context for a response.
     */
    private function buildResponseContext(DomainResponseInterface $response): array
    {
        $context = [
            'responseStatusCode' => (string) $response->getStatusCode(),
        ];

        if ($response instanceof DomainMessageHandlerResponse) {
            $context += [
                'messageHandler' => $response->getHandlerName(),
            ];
        }

        $payload = $response->getPayload();
        if ($payload instanceof Throwable) {
            $context += $this->buildThrowableContext($payload);
        }

        return $context;
    }

    /**
     * Builds the logger context for a throwable.
     */
    private function buildThrowableContext(Throwable $throwable): array
    {
        return [
            'exceptionClass' => \get_class($throwable),
            'exceptionMessage' => $throwable->getMessage(),
            'exceptionFile' => $throwable->getFile(),
            'exceptionLine' => $throwable->getLine(),
        ];
    }
}
