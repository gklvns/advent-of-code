<?php

namespace AdventOfCode\Year2025;

use AdventOfCode\File;
use AdventOfCode\Task;

final class Playground extends Task
{
    private static int $iterations = 1000;
    private static array $boxes = [];

    public static function run(string $file): void
    {
        if (str_ends_with($file, 'example.txt')) {
            self::$iterations = 10;
        }

        self::$boxes = array_map(
            static fn (string $line) => $line
                    |> (static fn (string $line) => explode(',', $line))
                    |> (static fn (array $coordinates) => array_map('intval', $coordinates)),
            File::lines($file)
        );

        self::multiplyThreeLargestCircuits();
        self::multiplyCoordinatesOfLastTwoJunctionBoxes();
    }

    public static function day(): int
    {
        return 8;
    }

    private static function multiplyThreeLargestCircuits(): void
    {
        [$circuits, $distances] = self::calculateDistances(self::$boxes);

        $i = 0;
        while (true) {
            [$circuits] = self::mergeCircuits($distances[$i], $circuits);

            if ($i === self::$iterations - 1) {
                break;
            }

            $i++;
        }

        $counts = array_map(static fn (array $circuit) => count($circuit), $circuits);

        sort($counts);
        $count = array_product(array_slice($counts, -3));

        self::result($count);
    }

    private static function multiplyCoordinatesOfLastTwoJunctionBoxes(): void
    {
        [$circuits, $distances] = self::calculateDistances(self::$boxes);

        $i = 0;
        while (true) {
            [$circuits, $distance] = self::mergeCircuits($distances[$i], $circuits);

            if (count($circuits) === 1) {
                $count = $distance[0][0] * $distance[1][0];
                break;
            }

            $i++;
        }

        self::result($count);
    }

    private static function calculateDistances(array $boxes): array
    {
        $circuits = $distances = [];

        for ($i = 0; $i < count($boxes); $i++) {
            $circuits[$i] = [$boxes[$i]];

            for ($j = $i + 1; $j < count($boxes); $j++) {
                [$xi, $yi, $zi] = $boxes[$i];
                [$xj, $yj, $zj] = $boxes[$j];

                $distances[] = [
                    $boxes[$i],
                    $boxes[$j],
                    sqrt(pow($xi - $xj, 2) + pow($yi - $yj, 2) + pow($zi - $zj, 2)),
                ];
            }
        }

        usort($distances, static fn (array $a, $b) => $a[2] <=> $b[2]);

        return [$circuits, $distances];
    }

    private static function mergeCircuits(array $distances, array $circuits): array
    {
        $distance = $distances;

        $j = array_find_key($circuits, static fn (array $circuit) => in_array($distance[0], $circuit));
        $k = array_find_key($circuits, static fn (array $circuit) => in_array($distance[1], $circuit));

        if ($circuits[$j] !== $circuits[$k]) {
            $circuits[$j] = array_merge($circuits[$j], $circuits[$k]);
            unset($circuits[$k]);
        }

        return [$circuits, $distance];
    }
}
