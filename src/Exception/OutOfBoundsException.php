<?php

declare(strict_types=1);

namespace JensSchulze\Collection\Exception;

/**
 * @author Jens Schulze, github.com/jensschulze
 */
class OutOfBoundsException extends \OutOfBoundsException
{
    public static function reasonTooLow(int $index): OutOfBoundsException
    {
        return new static(sprintf('Index "%d" too low', $index));
    }

    public static function reasonTooHigh(int $index): OutOfBoundsException
    {
        return new static(sprintf('Index "%d" too high', $index));
    }
}
