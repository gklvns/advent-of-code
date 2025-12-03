<?php

declare(strict_types=1);

namespace AdventOfCode\Year2025;

use AdventOfCode\File;
use AdventOfCode\Task;

final class PrintingDepartment extends Task
{
    private const string ROLL_OF_PAPER = '@';
    private const int    MIN_ADJACENT  = 4;
    private const array  ADJACENT      = [[0, -1], [1, -1], [1, 0], [1, 1], [0, 1], [-1, 1], [-1, 0], [-1, -1]];

    private static array $input = [];

    public static function run(string $file): void
    {
        self::$input = array_map(
            static fn (string $line) => $line |> str_split(...),
            File::lines($file)
        );

        self::countAccessiblePaperRolls();
        self::countRemovedAccessiblePaperRolls();
    }

    public static function day(): int
    {
        return 4;
    }

    private static function countAccessiblePaperRolls(): void
    {
        $count = 0;

        foreach (self::$input as $y => $cells) {
            foreach ($cells as $x => $cell) {
                if ($cell !== self::ROLL_OF_PAPER) {
                    continue;
                }

                if (self::isRollOfPaperAccessible($x, $y)) {
                    $count++;
                }
            }
        }

        self::result($count);
    }

    private static function countRemovedAccessiblePaperRolls(): void
    {
        $count = 0;

        while (true) {
            $accessible = false;

            foreach (self::$input as $y => $cells) {
                foreach ($cells as $x => $cell) {
                    if ($cell !== self::ROLL_OF_PAPER) {
                        continue;
                    }

                    if (self::isRollOfPaperAccessible($x, $y)) {
                        self::$input[$y][$x] = 'x';

                        $accessible = true;
                        $count++;
                    }
                }
            }

            if (!$accessible) {
                break;
            }
        }

        self::result($count);
    }

    private static function isRollOfPaperAccessible(int $x, int $y): bool
    {
        $adjacent = 0;

        foreach (self::ADJACENT as [$xx, $yy]) {
            if ((self::$input[$y + $yy][$x + $xx] ?? null) === self::ROLL_OF_PAPER) {
                $adjacent++;
            }
        }

        if ($adjacent < self::MIN_ADJACENT) {
            return true;
        }

        return false;
    }
}
