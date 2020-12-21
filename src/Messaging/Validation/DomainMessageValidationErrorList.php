<?php

namespace Morebec\Orkestra\Messaging\Validation;

use Morebec\Orkestra\Modeling\Collection;

/**
 * @extends Collection<DomainMessageValidationError>
 */
class DomainMessageValidationErrorList implements \Iterator, \Countable
{
    /**
     * @var Collection
     */
    private $errors;

    public function __construct(iterable $errors = [])
    {
        $this->errors = new Collection();
        foreach ($errors as $error) {
            $this->add($error);
        }
    }

    public function add(DomainMessageValidationErrorInterface $e): void
    {
        $this->errors->add($e);
    }

    /**
     * Merges a list of errors with the current errors and returns a new collection containing the merge of the two collections.
     *
     * @param DomainMessageValidationErrorList $errors
     */
    public function merge(self $errors): self
    {
        $merged = new self($this->errors);

        foreach ($errors as $error) {
            $merged->add($error);
        }

        return $merged;
    }

    public function isEmpty(): bool
    {
        return $this->errors->isEmpty();
    }

    public function toArray()
    {
        return $this->errors;
    }

    public function current()
    {
        return $this->errors->current();
    }

    public function next()
    {
        return $this->errors->next();
    }

    public function key()
    {
        return $this->errors->key();
    }

    public function valid()
    {
        return $this->errors->valid();
    }

    public function rewind()
    {
        $this->errors->rewind();
    }

    public function getCount(): int
    {
        return $this->count();
    }

    public function count()
    {
        return \count($this->errors);
    }
}
