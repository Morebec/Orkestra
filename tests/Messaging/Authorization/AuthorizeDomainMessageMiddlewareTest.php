<?php

namespace Tests\Morebec\Orkestra\Messaging\Authorization;

use Morebec\Orkestra\Messaging\Authorization\AuthorizeDomainMessageMiddleware;
use Morebec\Orkestra\Messaging\Authorization\DomainMessageAuthorizerInterface;
use Morebec\Orkestra\Messaging\Authorization\UnauthorizedDomainResponse;
use Morebec\Orkestra\Messaging\Authorization\UnauthorizedException;
use Morebec\Orkestra\Messaging\DomainMessageHandlerResponse;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\DomainResponseStatusCode;
use PHPUnit\Framework\TestCase;

class AuthorizeDomainMessageMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $authorizer = $this->createAuthorizer();
        $middleware = new AuthorizeDomainMessageMiddleware([$authorizer]);

        $message = $this->createMessage();

        $nextMiddleware = static function (DomainMessageInterface $domainMessage, DomainMessageHeaders $headers) {
            return new DomainMessageHandlerResponse('handlerName', DomainResponseStatusCode::SUCCEEDED());
        };

        $response = $middleware->handle($message, new DomainMessageHeaders(), $nextMiddleware);

        $this->assertInstanceOf(UnauthorizedDomainResponse::class, $response);
    }

    private function createAuthorizer(): DomainMessageAuthorizerInterface
    {
        return new class() implements DomainMessageAuthorizerInterface {
            public function authorize(DomainMessageInterface $domainMessage, DomainMessageHeaders $domainMessageHeaders): void
            {
                throw new UnauthorizedException('Not authorized');
            }

            public function supports(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers): bool
            {
                return true;
            }
        };
    }

    private function createMessage(): DomainMessageInterface
    {
        return new class() implements DomainMessageInterface {
            public static function getTypeName(): string
            {
                return 'message';
            }
        };
    }
}
