<?php

namespace Tests\Morebec\Orkestra\Messaging\Context;

use Morebec\Orkestra\DateTime\SystemClock;
use Morebec\Orkestra\Messaging\Context\BuildDomainContextMiddleware;
use Morebec\Orkestra\Messaging\Context\DomainContextManager;
use Morebec\Orkestra\Messaging\DomainMessageHandlerResponse;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\DomainResponseStatusCode;
use PHPUnit\Framework\TestCase;

class BuildDomainContextMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $manager = new DomainContextManager();
        $clock = new SystemClock();
        $middleware = new BuildDomainContextMiddleware($clock, $manager);

        $message = $this->getMessage();
        $middleware->handle($message, new DomainMessageHeaders(), static function ($a, $b) {
            return new DomainMessageHandlerResponse('handler', DomainResponseStatusCode::SUCCEEDED());
        });

        // Nested Message
        $this->assertNull($manager->getContext());

        $manager->startContext($this->getMessage(), new DomainMessageHeaders([
            DomainMessageHeaders::CORRELATION_ID => 'corr',
            DomainMessageHeaders::CAUSATION_ID => null,
            DomainMessageHeaders::MESSAGE_ID => 'messageId',
        ]));
        $middleware->handle($message, new DomainMessageHeaders(), static function ($a, $b) {
            return new DomainMessageHandlerResponse('handler', DomainResponseStatusCode::SUCCEEDED());
        });
        $this->assertNotNull($manager->getContext());
    }

    private function getMessage(): DomainMessageInterface
    {
        return new class() implements DomainMessageInterface {
            public static function getTypeName(): string
            {
                return 'test';
            }
        };
    }
}
