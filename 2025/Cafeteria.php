<?php

namespace AdventOfCode\Year2025;

use AdventOfCode\File;
use AdventOfCode\Task;

final class Cafeteria extends Task
{
    private static array $ranges = [];
    private static array $ingredients = [];

    public static function run(string $file): void
    {
        $lines = File::lines($file);

        foreach ($lines as $line) {
            $range = explode('-', $line);

            if (isset($range[1])) {
                self::$ranges[] = array_map('intval', $range);
            } else {
                self::$ingredients[] = intval($line);
            }
        }

        self::countFreshIngredients();
        self::countFreshIngredientsFromRanges();
    }

    public static function day(): int
    {
        return 5;
    }

    private static function countFreshIngredients(): void
    {
        $fresh = [];

        foreach (self::$ingredients as $ingredient) {
            foreach (self::$ranges as [$min, $max]) {
                if ($ingredient >= $min && $ingredient <= $max) {
                    $fresh[] = $ingredient;
                }
            }
        }

        $count = count(array_flip($fresh));

        self::result($count);
    }

    private static function countFreshIngredientsFromRanges(): void
    {
        $count = 0;

        usort(self::$ranges, fn (array $a, array $b) => $a[0] <=> $b[0]);

        [$min, $max] = self::$ranges[0];

        for ($i = 1; $i < count(self::$ranges); $i++) {
            [$start, $end] = self::$ranges[$i];

            if ($start <= $max) {
                $max = max($max, $end);
            } else {
                $count += $max - $min + 1;

                [$min, $max] = [$start, $end];
            }
        }

        $count += $max - $min + 1;

        self::result($count);
    }
}
