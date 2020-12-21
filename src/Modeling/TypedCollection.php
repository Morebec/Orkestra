<?php

namespace Morebec\Orkestra\Modeling;

class TypedCollection extends Collection
{
    /**
     * @var string
     */
    protected $className;

    public function __construct(string $className, iterable $elements = [])
    {
        $this->className = $className;
        parent::__construct($elements);
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
    protected function validateType($element): void
    {
        if (!is_a($element, $this->className, true)) {
            throw new \InvalidArgumentException(sprintf('Expected element of type "%s", got "%s"', $this->className, get_debug_type($element)));
        }
    }
}
