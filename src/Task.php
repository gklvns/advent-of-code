<?php

declare(strict_types=1);

namespace AdventOfCode;

use Stringable;

abstract class Task
{
    abstract public static function run(string $file): void;

    protected static function result(int $part, Stringable|string|float|int $result): void
    {
        echo sprintf('Day %02d (Part %d): %s', static::day(), $part, $result), PHP_EOL;
    }

    abstract public static function day(): int;
}
