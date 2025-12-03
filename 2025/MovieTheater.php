<?php

declare(strict_types=1);

namespace AdventOfCode\Year2025;

use AdventOfCode\File;
use AdventOfCode\Task;

final class MovieTheater extends Task
{
    private static array $tiles = [];

    public static function run(string $file): void
    {
        self::$tiles = array_map(
            static fn (string $line) => $line
                    |> (static fn (string $line) => explode(',', $line))
                    |> (static fn (array $coordinates) => array_map('intval', $coordinates)),
            File::lines($file)
        );

        self::findLargestRectangle();
        self::findLargestValidRectangle();
    }

    public static function day(): int
    {
        return 9;
    }

    private static function findLargestRectangle(): void
    {
        $area = 0;

        foreach (self::$tiles as $tile) {
            $candidates = array_filter(
                self::$tiles,
                static fn (array $candidate) => $candidate[0] !== $tile[0] && $candidate[1] !== $tile[1]
            );

            foreach ($candidates as $candidate) {
                $area = max($area, abs($candidate[0] - $tile[0] + 1) * abs($candidate[1] - $tile[1] + 1));
            }
        }

        self::result($area);
    }

    private static function findLargestValidRectangle(): void
    {
        $area = 0;
        $count = count(self::$tiles);

        $lines = [];
        for ($i = 0; $i < $count; $i++) {
            $j = ($i + 1) % $count;

            $direction = self::$tiles[$i][0] === self::$tiles[$j][0] ? 0 : 1;

            $lines[] = [$i, $j, $direction, self::$tiles[$i], self::$tiles[$j]];
        }

        for ($i = 0; $i < $count - 1; $i++) {
            $id = $lines[$i][2];
            $iv = self::$tiles[$lines[$i][0]][$id];

            for ($j = $i + 1; $j < $count; $j++) {
                $jd = $lines[$j][2];
                $jv = self::$tiles[$lines[$j][0]][$jd];

                if ($id != $jd) {
                    continue;
                }

                if (abs($iv - $jv) > 1) {
                    continue;
                }

                $nd = $id ? 0 : 1;

                $ir0 = self::$tiles[$lines[$i][0]][$nd];
                $ir1 = self::$tiles[$lines[$i][1]][$nd];
                $ir  = [min($ir0, $ir1), max($ir0, $ir1)];

                $jr0 = self::$tiles[$lines[$j][0]][$nd];
                $jr1 = self::$tiles[$lines[$j][1]][$nd];
                $jr  = [min($jr0, $jr1), max($jr0, $jr1)];

                if ($ir[0] <= $jr[1] && $ir[1] >= $jr[0]) {
                    break 2;
                }
            }
        }

        $pairs = [];
        for ($i = 0; $i < $count - 1; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                [$ix, $iy] = self::$tiles[$i];
                [$jx, $jy] = self::$tiles[$j];

                $pairs[] = [$i, $j, (abs($ix - $jx) + 1) * (abs($iy - $jy) + 1), self::$tiles[$i], self::$tiles[$j]];
            }
        }

        usort($pairs, fn ($a, $b) => $b[2] <=> $a[2]);

        foreach ($pairs as $pair) {
            [$ix, $iy] = self::$tiles[$pair[0]];
            [$jx, $jy] = self::$tiles[$pair[1]];

            $r = [
                [min($ix, $jx), max($ix, $jx)],
                [min($iy, $jy), max($iy, $jy)],
            ];

            foreach ($lines as $line) {
                $direction = $line[2];
                $nd        = $direction ? 0 : 1;

                $dv = self::$tiles[$line[0]][$direction];
                if ($dv <= $r[$direction][0] || $dv >= $r[$direction][1]) {
                    continue;
                }

                $ndr0 = self::$tiles[$line[0]][$nd];
                $ndr1 = self::$tiles[$line[1]][$nd];
                $ndr  = [min($ndr0, $ndr1), max($ndr0, $ndr1)];
                if ($ndr[0] >= $r[$nd][1] || $ndr[1] <= $r[$nd][0]) {
                    continue;
                }

                continue 2;
            }

            $area = $pair[2];
            break;
        }

        self::result($area);
    }
}
