<?php

namespace AdventOfCode\Year2025;

use AdventOfCode\File;
use AdventOfCode\Task;

final class Reactor extends Task
{
    private static array $graphs = [];

    public static function run(string $file): void
    {
        foreach (File::lines($file) as $line) {
            [$path, $paths] = explode(': ', $line);

            self::$graphs[$path] = $paths |> (static fn (string $paths) => explode(' ', $paths));
        }

        self::countDifferentPaths();
        self::countDifferentPathsVisit();
    }

    public static function day(): int
    {
        return 11;
    }

    private static function countDifferentPaths(): void
    {
        $count = self::countPaths('you', 'out');

        self::result($count);
    }

    private static function countDifferentPathsVisit(): void
    {
        $count = self::countPaths('svr', 'dac') * self::countPaths('dac', 'fft') * self::countPaths('fft', 'out');
        $count += self::countPaths('svr', 'fft') * self::countPaths('fft', 'dac') * self::countPaths('dac', 'out');

        self::result($count);
    }

    private static function countPaths(string $source, string $destination): int
    {
        static $cache = [];

        $key = "$source->$destination";

        if (isset($cache[$key])) {
            return $cache[$key];
        }

        if ($source === $destination) {
            $cache[$key] = 1;

            return 1;
        }

        $total = 0;

        $neighbors = self::$graphs[$source] ?? [];
        foreach ($neighbors as $neighbor) {
            $total += self::countPaths($neighbor, $destination);
        }

        $cache[$key] = $total;

        return $total;
    }
}