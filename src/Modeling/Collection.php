<?php

namespace Morebec\Orkestra\Modeling;

/**
 * @template T
 */
class Collection implements \Iterator, \Countable
{
    /**
     * @var iterable
     */
    protected $elements;

    public function __construct(iterable $elements = [])
    {
        $this->elements = [];
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * Flattens each {@link \Iterator} element of this collection as a new collection.
     *
     * @return $this
     */
    public function flatten(): self
    {
        $return = [];
        foreach ($this->elements as $element) {
            if ($element instanceof \Iterator) {
                foreach ($element as $e) {
                    $return[] = $e;
                }
            } else {
                $return[] = $element;
            }
        }

        return new static($return);
    }

    /**
     * Projects each element of this collection into a new collection preserving
     * the index.
     *
     * @return $this
     */
    public function map(callable $p): self
    {
        return new self(array_map($p, $this->elements));
    }

    /**
     * Filters this collection and returns a new collection with the results.
     *
     * @return $this
     */
    public function filter(callable $p): self
    {
        return new static(array_filter($this->elements, $p));
    }

    /**
     * Indicates if all elements of this collection satisfy a condition.
     */
    public function areAll(callable $p): bool
    {
        $c = $this->filter($p);

        return $c->count() === $this->count();
    }

    /**
     * Indicates if any elements of this collection satisfies a condition.
     */
    public function isAny(callable $p): bool
    {
        $c = $this->filter($p);

        return !$c->isEmpty();
    }

    /**
     * Returns the element at a given index.
     *
     * @param $index
     *
     * @return T
     */
    public function get($index)
    {
        return $this->elements[$index];
    }

    /**
     * Returns the element at a given index or a default value it is out of rance.
     *
     * @param $index
     * @param null $default
     *
     * @return T|mixed
     */
    public function getOrDefault($index, $default = null)
    {
        return \array_key_exists($index, $this->elements) ? $this->get($index) : $default;
    }

    /**
     * Returns the first element.
     *
     * @return T
     */
    public function getFirst()
    {
        $firstKey = array_key_first($this->elements);

        return $this->get($firstKey);
    }

    /**
     * Finds the first element matching a search predicate and returns it,
     * or returns a default value if none matched.
     *
     * @param mixed $default
     *
     * @return mixed|null
     */
    public function findFirstOrDefault(callable $p, $default = null)
    {
        foreach ($this->elements as $e) {
            if ($p($e)) {
                return $e;
            }
        }

        return $default;
    }

    /**
     * Returns the last element.
     *
     * @return T
     */
    public function getLast()
    {
        $lastKey = array_key_last($this->elements);

        return $this->get($lastKey);
    }

    /**
     * Inverts the order of the elements of this collection and returns it as a new collection.
     */
    public function reversed(): self
    {
        return new static(array_reverse($this->elements));
    }

    /**
     * Extract a slice of the collection as a new collection.
     *
     * @param int      $offset
     *                         If offset is non-negative, the sequence will
     *                         start at that offset in the array. If
     *                         offset is negative, the sequence will
     *                         start that far from the end of the array.
     * @param int|null $length
     *                         If length is given and is positive, then
     *                         the sequence will have that many elements in it. If
     *                         length is given and is negative then the
     *                         sequence will stop that many elements from the end of the
     *                         array. If it is omitted, then the sequence will have everything
     *                         from offset up until the end of the
     *                         array.
     */
    public function slice(int $offset, ?int $length = null): self
    {
        $elements = \array_slice($this->elements, $offset, $length);

        return new static($elements);
    }

    /**
     * Splits this collection into a collection of collections.
     * Each contained collection will have $length elements or less (for the last one).
     */
    public function chunk(int $length): self
    {
        if ($length < 1) {
            throw  new \InvalidArgumentException(sprintf('The length must be a positive integer, received "%s".', $length));
        }

        $chunks = array_chunk($this->elements, $length);

        $collection = new static();
        foreach ($chunks as $chunk) {
            $collection->add(new static($chunk));
        }

        return $collection;
    }

    /**
     * Applies an accumulator callback over the elements of this collection.
     *
     * @param callable   $p
     *                            The callback function. Signature is <pre>callback ( mixed $carry , mixed $element ) : mixed</pre>
     *                            <blockquote>mixed <var>$carry</var> <p>The return value of the previous iteration; on the first iteration it holds the value of <var>$initial</var>.</p></blockquote>
     *                            <blockquote>mixed <var>$element</var> <p>Holds the current iteration value of the <var>$input</var></p></blockquote>
     *                            </p>
     * @param mixed|null $initial
     *
     * @return mixed
     */
    public function reduce(callable $p, $initial = null)
    {
        return array_reduce($this->elements, $p, $initial);
    }

    /**
     * Appends a value to the end of this collection.
     *
     * @param T $element
     */
    public function add($element): void
    {
        $this->elements[] = $element;
    }

    /**
     * Adds an element at the beginning of this collection.
     *
     * @param T $element
     */
    public function prepend($element): void
    {
        array_unshift($this->elements, $element);
    }

    /**
     * Clears this array removing all elements it contains.
     */
    public function clear(): void
    {
        $this->elements = [];
    }

    /**
     * Converts this collection to an Array.
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    public function current()
    {
        return current($this->elements);
    }

    public function next()
    {
        return next($this->elements);
    }

    public function key()
    {
        return key($this->elements);
    }

    public function valid()
    {
        return \array_key_exists($this->key(), $this->elements);
    }

    public function rewind()
    {
        reset($this->elements);
    }

    public function count()
    {
        return \count($this->elements);
    }

    /**
     * Typed alias of self::count().
     */
    public function getCount(): int
    {
        return $this->count();
    }

    /**
     * Indicates if this collection is empty.
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * Returns a copy of this collection.
     *
     * @return $this
     */
    public function copy(): self
    {
        return new static($this->elements);
    }
}
