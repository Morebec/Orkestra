<?php

namespace Tests\Morebec\Orkestra\Messaging\Validation;

use Morebec\Orkestra\Messaging\Validation\DomainMessageValidationErrorInterface;
use Morebec\Orkestra\Messaging\Validation\DomainMessageValidationErrorList;
use PHPUnit\Framework\TestCase;

class DomainMessageValidationErrorListTest extends TestCase
{
    public function testAdd()
    {
        $errors = new DomainMessageValidationErrorList();

        $errors->add($this->createError('Invalid test'));

        $this->assertFalse($errors->isEmpty());
    }

    public function testMerge()
    {
        $errorsA = new DomainMessageValidationErrorList([
            $this->createError('1'),
            $this->createError('2'),
        ]);
        $errorsB = new DomainMessageValidationErrorList([
            $this->createError('3'),
        ]);

        $errors = $errorsB->merge($errorsA);

        $this->assertCount(3, $errors);
    }

    private function createError(string $message): DomainMessageValidationErrorInterface
    {
        return new class($message) implements DomainMessageValidationErrorInterface {
            /**
             * @var string
             */
            private $message;

            public function __construct(string $message)
            {
                $this->message = $message;
            }

            public function getMessage(): string
            {
                return $this->message;
            }

            public function property(): string
            {
                return 'property';
            }

            public function getValue()
            {
                return 'hello-world';
            }
        };
    }
}
