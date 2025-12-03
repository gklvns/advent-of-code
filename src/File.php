<?php

declare(strict_types=1);

namespace AdventOfCode;

final class File
{
    public static function contents(string $file): string
    {
        return file_get_contents($file);
    }

    /**
     * @return string[]
     */
    public static function lines(string $file, int $flags = FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES): array
    {
        return file($file, $flags);
    }

    public static function path(int $year, int $day, string $file): string
    {
        return sprintf('data/%d/%d/%s', $year, $day, $file);
    }
}
