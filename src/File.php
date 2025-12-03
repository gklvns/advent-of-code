<?php

declare(strict_types=1);

namespace AdventOfCode;

final class File
{
    public static function contents(int $day, string $file): string
    {
        return file_get_contents(self::input($day, $file));
    }

    public static function input(int $day, string $file): string
    {
        return realpath(sprintf('data/%d/%s', $day, $file));
    }

    /**
     * @return string[]
     */
    public static function lines(
        int $day,
        string $file,
        int $flags = FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
    ): array {
        return file(self::input($day, $file), $flags) |> (static fn(array $lines) => array_map('trim', $lines));
    }
}
