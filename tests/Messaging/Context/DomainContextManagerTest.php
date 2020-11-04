<?php

namespace Tests\Morebec\Orkestra\Messaging\Context;

use Morebec\Orkestra\Messaging\Context\DomainContextManager;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use PHPUnit\Framework\TestCase;

class DomainContextManagerTest extends TestCase
{
    public function testManageContext(): void
    {
        $manager = new DomainContextManager();
        $message = $this->getMessage();

        $manager->startContext($this->getMessage(), new DomainMessageHeaders([
            DomainMessageHeaders::CORRELATION_ID => 'corr',
            DomainMessageHeaders::CAUSATION_ID => null,
            DomainMessageHeaders::MESSAGE_ID => 'messageId',
        ]));

        $manager->startContext($this->getMessage(), new DomainMessageHeaders([
            DomainMessageHeaders::CORRELATION_ID => 'corr',
            DomainMessageHeaders::CAUSATION_ID => null,
            DomainMessageHeaders::MESSAGE_ID => 'messageId',
        ]));

        $manager->endContext();

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
