<?php

namespace Morebec\Orkestra\TestModule\Infrastructure\Service;

/**
 * This Class represents a Person
 */
class Person
{
    /** @var string Fullname of the person */
    private $fullname;

    /** @var int Age of the person */
    private $age;


    /**
     * Constructs a Person instance
     * @param string $fullname Fullname of the person
     * @param int $age Age of the person
     */
    public function __construct(string $fullname, int $age = 10)
    {
        $this->age = $age;
        $this->fullname = $fullname;
    }


    /**
     * Returns the value of fullname
     * @return string Value of fullname
     */
    public function getFullname(): string
    {
        return $this->fullname;
    }


    /**
     * Returns the value of age
     * @return int Value of age
     */
    public function getAge(): int
    {
        return $this->age;
    }
}

