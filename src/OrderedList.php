<?php

declare(strict_types=1);

namespace JensSchulze\Collection;

use JensSchulze\Collection\Exception\OutOfBoundsException;
use function array_splice;
use function count;

class OrderedList implements Collection
{
    private $position = 0;
    private $storage = [];
    private $size;

    /**
     * OrderedList constructor.
     *
     * @param mixed[] $listData
     */
    public function __construct(array $listData)
    {
        foreach ($listData as $item) {
            $this->storage[] = $item;
        }

        $this->size = count($this->storage);
    }

    /**
     * Return the number of elements in this list
     *
     * @link https://docs.oracle.com/javase/8/docs/api/java/util/List.html
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * Return the current element
     *
     * @link https://php.net/manual/en/iterator.current.php
     *
     * @return mixed
     */
    public function current()
    {
        return $this->storage[$this->position];
    }

    /**
     * Move forward to next element
     *
     * @link https://php.net/manual/en/iterator.next.php
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * Return the key of the current element
     *
     * @link https://php.net/manual/en/iterator.key.php
     */
    public function key(): ?int
    {
        return $this->position;
    }

    /**
     * Checks if current position is valid
     *
     * @link https://php.net/manual/en/iterator.valid.php
     */
    public function valid(): bool
    {
        return isset($this->storage[$this->position]);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link https://php.net/manual/en/iterator.rewind.php
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Move up the element at the specified index. The index must be at greater than 0 and less than size - 1.
     *
     * @throws \JensSchulze\Collection\Exception\OutOfBoundsException
     */
    public function up(int $index): Collection
    {
        $listData = $this->storage;

        if ($index <= 0) {
            throw OutOfBoundsException::reasonTooLow($index);
        }

        if ($index >= $this->size) {
            throw OutOfBoundsException::reasonTooHigh($index);
        }

        if ($this->size > 0) {
            [$listData[$index - 1], $listData[$index]] = [$listData[$index], $listData[$index - 1]];
        }

        return new static($listData);
    }

    /**
     * Move down the element at the specified index. The index must be at least 0 and less than size - 1.
     *
     * @throws \JensSchulze\Collection\Exception\OutOfBoundsException
     */
    public function down(int $index): Collection
    {
        $listData = $this->storage;

        if ($index < 0) {
            throw OutOfBoundsException::reasonTooLow($index);
        }

        if ($index >= $this->size - 1) {
            throw OutOfBoundsException::reasonTooHigh($index);
        }

        if ($this->size > 0) {
            [$listData[$index + 1], $listData[$index]] = [$listData[$index], $listData[$index + 1]];
        }

        return new static($listData);
    }

    /**
     * Delete an element at the specified index. The index must be at least 0 and less than size.
     *
     * @throws \JensSchulze\Collection\Exception\OutOfBoundsException
     */
    public function delete(int $index): Collection
    {
        $listData = $this->storage;

        if ($index < 0) {
            throw OutOfBoundsException::reasonTooLow($index);
        }

        if ($index >= $this->size) {
            throw OutOfBoundsException::reasonTooHigh($index);
        }

        unset($listData[$index]);

        return new static($listData);
    }

    /**
     * Insert an element at the specified index. The index must be at least 0 and size at the most.
     *
     * @param mixed $element
     *
     * @throws \JensSchulze\Collection\Exception\OutOfBoundsException
     */
    public function insert($element, int $index): Collection
    {
        $listData = $this->storage;

        if ($index < 0) {
            throw OutOfBoundsException::reasonTooLow($index);
        }

        if ($index > $this->size) {
            throw OutOfBoundsException::reasonTooHigh($index);
        }

        array_splice($listData, $index, 0, $element);

        return new static($listData);
    }

    public function asArray(): array
    {
        return $this->storage;
    }
}
