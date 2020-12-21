<?php

namespace Morebec\Orkestra\Messaging\Validation;

use Morebec\Orkestra\Modeling\TypedCollection;

/**
 * @extends Collection<DomainMessageValidationError>
 */
class DomainMessageValidationErrorList extends TypedCollection
{
    public function __construct(iterable $errors = [])
    {
        parent::__construct(DomainMessageValidationErrorInterface::class, $errors);
    }

    /**
     * @param DomainMessageValidationErrorInterface $element
     */
    public function add($element): void
    {
        parent::add($element);
    }

    /**
     * @param DomainMessageValidationErrorInterface $element
     */
    public function prepend($element): void
    {
        parent::prepend($element);
    }

    /**
     * @return DomainMessageValidationErrorInterface
     */
    public function getFirst()
    {
        return parent::getFirst();
    }

    /**
     * @return DomainMessageValidationErrorInterface
     */
    public function getLast()
    {
        return parent::getLast();
    }

    /**
     * @param $index
     *
     * @return DomainMessageValidationErrorInterface
     */
    public function get($index)
    {
        return parent::get($index);
    }

    /**
     * Merges a list of errors with the current errors and returns a new collection containing the merge of the two collections.
     *
     * @param DomainMessageValidationErrorList $errors
     *
     * @return DomainMessageValidationErrorList
     */
    public function merge(self $errors): self
    {
        $merged = new self($this->elements);

        foreach ($errors as $error) {
            $merged->add($error);
        }

        return $merged;
    }
}
