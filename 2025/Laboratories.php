<?php

namespace AdventOfCode\Year2025;

use AdventOfCode\File;
use AdventOfCode\Task;

final class Laboratories extends Task
{
    private static array $space = [];

    public static function run(string $file): void
    {
        self::$space = File::lines($file);

        self::countBeamSplit();
        self::countQuantumBeamSplit();
    }

    public static function day(): int
    {
        return 7;
    }

    private static function countBeamSplit(): void
    {
        $count = 0;
        $beams = [strpos(self::$space[0], 'S')];

        for ($i = 1; $i < count(self::$space); $i++) {
            foreach ($beams as $j => $beam) {
                if (self::$space[$i][$beam] !== '^') {
                    continue;
                }

                unset($beams[$j]);

                foreach ([$beam - 1, $beam + 1] as $next) {
                    if (in_array($next, $beams)) {
                        continue;
                    }

                    $beams[] = $next;
                }

                $count++;
            }
        }

        self::result($count);
    }

    private static function countQuantumBeamSplit(): void
    {
        $cache = [];
        $start = strpos(self::$space[0], 'S');
        $count = self::countTimelines($cache, 0, $start);

        self::result($count);
    }

    private static function countTimelines(array &$cache, int $y, int $x): int
    {
        if ($cache[$y][$x] ?? false) {
            return $cache[$y][$x];
        }

        if ($y >= count(self::$space)) {
            return $cache[$y][$x] = 1;
        }

        if (self::$space[$y][$x] === '.' || self::$space[$y][$x] === 'S') {
            return $cache[$y][$x] = self::countTimelines($cache, $y + 1, $x);
        } elseif (self::$space[$y][$x] === '^') {
            $count = self::countTimelines($cache, $y, $x - 1) + self::countTimelines($cache, $y, $x + 1);

            return $cache[$y][$x] = $count;
        }

        return $cache[$y][$x] = 0;
    }
}