<?php

declare(strict_types=1);

namespace AdventOfCode;

use Stringable;

abstract class Task
{
    private static int $part = 1;

    abstract public static function run(string $file): void;

    protected static function result(Stringable|string|float|int $result): void
    {
        $part = self::$part === 2 ? self::$part-- : self::$part++;

        echo sprintf('Day %02d (Part %d): %s', static::day(), $part, $result), PHP_EOL;
    }

    abstract public static function day(): int;
}
