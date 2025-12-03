<?php

declare(strict_types=1);

namespace AdventOfCode\TwentyFive;

use AdventOfCode\File;
use AdventOfCode\Task;

final class GiftShop extends Task
{
    private static array $input = [];

    public static function run(string $file): void
    {
        $input = File::contents(self::day(), $file);

        self::$input = array_map(
            static fn(string $range) => explode('-', $range),
            $input |> (static fn(string $input) => explode(',', $input))
        );

        self::countIdsWithSomeSequence();
        self::countIdsWithAnyRepeatedSequence();
    }

    public static function day(): int
    {
        return 2;
    }

    private static function countIdsWithSomeSequence(): void
    {
        $ids = [];

        foreach (self::$input as [$min, $max]) {
            for ($i = intval($min); $i <= intval($max); $i++) {
                $number = strval($i);
                $length = strlen($number);

                if ($length % 2 !== 0) {
                    continue;
                }

                $half = intval($length / 2);

                if (substr($number, 0, $half) === substr($number, $half)) {
                    $ids[] = $i;
                }
            }
        }

        $count = array_sum($ids);

        self::result(1, $count);
    }

    private static function countIdsWithAnyRepeatedSequence(): void
    {
        $ids = [];

        foreach (self::$input as [$min, $max]) {
            for ($i = intval($min); $i <= intval($max); $i++) {
                $number = strval($i);
                $half = (strlen($max) / 2) |> floor(...) |> intval(...);

                for ($j = 0; $j <= $half; $j++) {
                    $pattern = substr($number, 0, $j);

                    if ($number !== $pattern && str_replace($pattern, '', $number) === '' && !in_array($i, $ids)) {
                        $ids[] = $i;
                    }
                }
            }
        }

        $count = array_sum($ids);

        self::result(2, $count);
    }
}
