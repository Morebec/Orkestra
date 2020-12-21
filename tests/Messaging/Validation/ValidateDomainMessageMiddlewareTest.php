<?php

namespace Tests\Morebec\Orkestra\Messaging\Validation;

use Morebec\Orkestra\Messaging\DomainMessageHandlerResponse;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\DomainResponseStatusCode;
use Morebec\Orkestra\Messaging\Validation\DomainMessageValidationError;
use Morebec\Orkestra\Messaging\Validation\DomainMessageValidationErrorList;
use Morebec\Orkestra\Messaging\Validation\DomainMessageValidatorInterface;
use Morebec\Orkestra\Messaging\Validation\InvalidDomainMessageResponse;
use Morebec\Orkestra\Messaging\Validation\ValidateDomainMessageMiddleware;
use PHPUnit\Framework\TestCase;

class ValidateDomainMessageMiddlewareTest extends TestCase
{
    public function testHandle(): void
    {
        $validator = $this->createValidator();

        $middleware = new ValidateDomainMessageMiddleware([$validator]);

        $headers = new DomainMessageHeaders();
        $nextMiddleware = static function (DomainMessageInterface $domainMessage, DomainMessageHeaders $headers) {
            return new DomainMessageHandlerResponse('handlerName', DomainResponseStatusCode::SUCCEEDED());
        };

        $message = $this->createMessage();
        $response = $middleware->handle($message, $headers, $nextMiddleware);

        $this->assertInstanceOf(InvalidDomainMessageResponse::class, $response);
        $this->assertEquals($response->getStatusCode(), DomainResponseStatusCode::INVALID());
        $this->assertInstanceOf(DomainMessageValidationErrorList::class, $response->getPayload());
    }

    private function createMessage(): DomainMessageInterface
    {
        return new class() implements DomainMessageInterface {
            /** @var string */
            public $property;

            public static function getTypeName(): string
            {
                return 'message';
            }
        };
    }

    private function createValidator(): DomainMessageValidatorInterface
    {
        return new class() implements DomainMessageValidatorInterface {
            public function validate(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers): DomainMessageValidationErrorList
            {
                $errors = new DomainMessageValidationErrorList();

                if (!$domainMessage->property) {
                    $errors->add(new DomainMessageValidationError(
                        'Property must not be blank',
                        'property',
                        $domainMessage->property
                    ));
                }

                return $errors;
            }

            public function supports(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers): bool
            {
                return true;
            }
        };
    }
}
