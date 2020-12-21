<?php

namespace Morebec\Orkestra\Modeling;

class TypedCollection extends Collection
{
    /**
     * @var string
     */
    private $className;

    public function __construct(string $className, iterable $elements = [])
    {
        parent::__construct($elements);
        $this->className = $className;
    }

    public function add($element): void
    {
        $this->validateType($element);
        parent::add($element);
    }

    public function prepend($element): void
    {
        $this->validateType($element);
        parent::prepend($element);
    }

    /**
     * @param $element
     */
    private function validateType($element): void
    {
        if (is_a($element, $this->className, true)) {
            throw new \InvalidArgumentException(sprintf('Expected element of type "%s", got "%s"', $this->className, \get_class($element)));
        }
    }
}
