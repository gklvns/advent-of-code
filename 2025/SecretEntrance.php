<?php

declare(strict_types=1);

namespace AdventOfCode\Year2025;

use AdventOfCode\File;
use AdventOfCode\Math;
use AdventOfCode\Task;

final class SecretEntrance extends Task
{
    private const int DIAL      = 50;
    private const int DIAL_SIZE = 100;

    private static array $input = [];

    public static function run(string $file): void
    {
        self::$input = array_map(
            static fn (string $line) => $line
                    |> (static fn (string $line) => str_replace(['L', 'R'], ['-', ''], $line))
                    |> intval(...),
            File::lines($file)
        );

        self::countRotationsLeadingToZero();
        self::countRotationsLeadingToAndCrossingZero();
    }

    public static function day(): int
    {
        return 1;
    }

    private static function countRotationsLeadingToZero(): void
    {
        $count = 0;
        $dial  = self::DIAL;

        foreach (self::$input as $turn) {
            $dial = Math::mod($dial + $turn, self::DIAL_SIZE);

            if ($dial === 0) {
                $count++;
            }
        }

        self::result($count);
    }

    private static function countRotationsLeadingToAndCrossingZero(): void
    {
        $count = 0;
        $dial  = self::DIAL;

        foreach (self::$input as $turn) {
            [$div, $mod] = Math::divmod($turn, self::DIAL_SIZE * ($turn <=> 0));

            if (($turn < 0 && $dial && $dial + $mod <= 0) || ($dial + $mod >= self::DIAL_SIZE)) {
                $count++;
            }

            $count += $div;

            $dial = Math::mod($dial + $turn, self::DIAL_SIZE);
        }

        self::result($count);
    }
}
