<?php

namespace Tests\Morebec\Orkestra\Modeling;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    public function test__construct(): void
    {
        $enum = new FakeEnum(FakeEnum::NAME);
        $this->assertEquals(new FakeEnum(FakeEnum::NAME), $enum);

        $this->expectException(InvalidArgumentException::class);
        new FakeEnum('this value does not exist');
    }

    public function testIsValidValue(): void
    {
        $this->assertFalse(FakeEnum::isValidValue('not_valid'));
        $this->assertTrue(FakeEnum::isValidValue(FakeEnum::VALUE));
        $this->assertTrue(FakeEnum::isValidValue(FakeEnum::NAME));
    }

    public function testIsValidName(): void
    {
        $this->assertFalse(FakeEnum::isValidName('not_valid'));
        $this->assertTrue(FakeEnum::isValidName('VALUE'));
        $this->assertTrue(FakeEnum::isValidName('NAME'));
    }

    public function testGetValue(): void
    {
        $enum = new FakeEnum(FakeEnum::NAME);
        $this->assertEquals(FakeEnum::NAME, $enum->getValue());
    }

    public function test__callStatic(): void
    {
        $enum = new FakeEnum(FakeEnum::NAME);
        $this->assertEquals($enum, FakeEnum::NAME());
    }

    public function testIsEqualTo(): void
    {
        $enum = new FakeEnum(FakeEnum::NAME);
        $this->assertTrue($enum->isEqualTo(FakeEnum::NAME()));
        $this->assertFalse($enum->isEqualTo(FakeEnum::VALUE()));
    }

    public function test__toString(): void
    {
        $enum = new FakeEnum(FakeEnum::NAME);
        $this->assertEquals(FakeEnum::NAME, (string) $enum);
    }
}
