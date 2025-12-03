<?php

declare(strict_types=1);

namespace AdventOfCode\Year2025;

use AdventOfCode\File;
use AdventOfCode\Task;

final class Lobby extends Task
{
    private const int LENGTH = 12;

    private static array $input = [];

    public static function run(string $file): void
    {
        self::$input = File::lines($file);

        self::countMaximumJoltageWihtTwoBatteries();
        self::countMaximumJoltageWihtTwelveBatteries();
    }

    public static function day(): int
    {
        return 3;
    }

    private static function countMaximumJoltageWihtTwoBatteries(): void
    {
        $max = [];

        foreach (self::$input as $i => $bank) {
            $length = strlen($bank);

            for ($j = 0; $j < $length; $j++) {
                for ($k = $j + 1; $k < $length; $k++) {
                    $max[$i] = max($max[$i] ?? 0, intval($bank[$j] . $bank[$k]));
                }
            }
        }

        $count = array_sum($max);

        self::result($count);
    }

    private static function countMaximumJoltageWihtTwelveBatteries(): void
    {
        $max = [];

        foreach (self::$input as $bank) {
            $k = strlen($bank) - self::LENGTH;

            if ($k < 0) {
                $max[] = str_pad($bank, self::LENGTH, '0');
                break;
            }

            $stack = [];

            foreach (str_split($bank) as $battery) {
                $battery = intval($battery);

                while ($k > 0 && !empty($stack) && end($stack) < $battery) {
                    array_pop($stack);
                    $k--;
                }

                $stack[] = $battery;
            }

            while ($k > 0) {
                array_pop($stack);
                $k--;
            }

            $max[] = implode('', array_slice($stack, 0, self::LENGTH));
        }

        $count = array_sum($max);

        self::result($count);
    }
}
