<?php

namespace Tests\Morebec\Orkestra\EventSourcing\Upcasting;

use Morebec\Orkestra\EventSourcing\Upcasting\AbstractMultiMessageUpcaster;
use Morebec\Orkestra\EventSourcing\Upcasting\AbstractSingleMessageUpcaster;
use Morebec\Orkestra\EventSourcing\Upcasting\UpcastableMessage;
use Morebec\Orkestra\EventSourcing\Upcasting\UpcasterChain;
use Morebec\Orkestra\EventSourcing\Upcasting\UpcasterInterface;
use PHPUnit\Framework\TestCase;

class UpcasterChainTest extends TestCase
{
    public function testUpcast(): void
    {
        $chain = new UpcasterChain([
            $this->getUpcasterA(),
            $this->getUpcasterB(),
        ]);

        $data = [
            'fullname' => 'John Doe',
        ];
        $result = $chain->upcast(new UpcastableMessage($data));

        $this->assertCount(2, $result);
        $this->assertArrayHasKey('firstName', $result[0]->data);
        $this->assertArrayHasKey('lastName', $result[1]->data);
    }

    private function getUpcasterA(): UpcasterInterface
    {
        return new class() extends AbstractSingleMessageUpcaster {
            public function __construct()
            {
                parent::__construct('');
            }

            public function supports(UpcastableMessage $message): bool
            {
                return true;
            }

            protected function doUpcast(UpcastableMessage $message): UpcastableMessage
            {
                [$firstName, $lastName] = explode(' ', $message->data['fullname']);

                return new UpcastableMessage([
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                ]);
            }
        };
    }

    private function getUpcasterB(): UpcasterInterface
    {
        return new class() extends AbstractMultiMessageUpcaster {
            public function __construct()
            {
                parent::__construct('');
            }

            public function doUpcast(UpcastableMessage $message): array
            {
                return [
                    new UpcastableMessage(['firstName' => $message->data['firstName']]),
                    new UpcastableMessage(['lastName' => $message->data['lastName']]),
                ];
            }

            public function supports(UpcastableMessage $message): bool
            {
                return true;
            }
        };
    }
}
