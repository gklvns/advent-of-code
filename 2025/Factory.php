<?php

namespace AdventOfCode\Year2025;

use AdventOfCode\File;
use AdventOfCode\Task;

final class Factory extends Task
{
    private static array $machines = [];

    public static function run(string $file): void
    {
        self::$machines = File::lines($file) |> (static function (array $lines) {
                return array_map(static function (string $line) {
                    $matches = [];
                    preg_match_all(
                        '/^\[(?<diagram>[.#]+)]\s(?<schematics>.*?)\s\{(?<joltage>[0-9,]+)}$/',
                        $line,
                        $matches
                    );

                    return [
                        'diagram' => reset($matches['diagram'])
                                |> (static fn (string $diagram) => str_replace(['.', '#'], ['0', '1'], $diagram))
                                |> str_split(...)
                                |> (static fn (array $indicators) => array_filter($indicators))
                                |> (static fn (array $indicators) => array_map('intval', $indicators)),
                        'schematics' => reset($matches['schematics'])
                                |> (static fn (string $schematics) => explode(
                                    ') (',
                                    trim($schematics, '()')
                                ))
                                |> (static fn (array $schematics) => array_map(
                                    static fn (string $buttons) => explode(',', $buttons),
                                    $schematics
                                ))
                                |> (static fn (array $buttons) => array_map(
                                    static fn (array $buttons) => array_map('intval', $buttons),
                                    $buttons
                                )),
                        'joltage' => reset($matches['joltage'])
                                |> (static fn (string $joltage) => explode(',', $joltage))
                                |> (static fn (array $joltage) => array_map('intval', $joltage)),
                    ];
                }, $lines);
            });

        self::countFewestButtonPresses();
        self::countFewestButtonPressesForJoltage();
    }

    public static function day(): int
    {
        return 10;
    }

    private static function countFewestButtonPresses(): void
    {
        $total = 0;

        foreach (self::$machines as $machine) {
            $target = array_keys($machine['diagram']);

            for ($count = 0; $count <= count($machine['schematics']); $count++) {
                $found        = false;
                $combinations = self::combinations($machine['schematics'], $count);

                foreach ($combinations as $attempt) {
                    $lights = [];

                    foreach ($attempt as $button) {
                        $lights = array_merge(array_diff($lights, $button), array_diff($button, $lights));
                        sort($lights);
                    }

                    if (count($lights) === count($target) && empty(array_diff_assoc($lights, $target))) {
                        $total += $count;
                        $found = true;
                        break;
                    }
                }

                if ($found) {
                    break;
                }
            }
        }

        self::result($total);
    }

    private static function countFewestButtonPressesForJoltage(): void
    {
        $total = 0;

        foreach (self::$machines as $machine) {
            $columns  = [];
            $affected = [];
            $targets  = $machine['joltage'];
            $counters = count($targets);

            foreach ($machine['schematics'] as $buttons) {
                $vector  = array_fill(0, $counters, 0.0);
                $indices = [];

                $effect = false;
                foreach ($buttons as $button) {
                    if ($button >= 0 && $button < $counters) {
                        $vector[$button] = 1.0;

                        $indices[] = $button;

                        $effect = true;
                    }
                }

                if ($effect) {
                    $columns[]  = $vector;
                    $affected[] = $indices;
                }
            }

            if (empty($columns)) {
                continue;
            }

            $presses = self::solve($columns, $targets, $affected);

            if ($presses !== null) {
                $total += $presses;
            }
        }

        self::result($total);
    }

    private static function combinations(array $iterable, int $r): array
    {
        $n       = count($iterable);
        $results = [];

        if ($r < 0 || $r > $n) {
            return [];
        }

        if ($r === 0) {
            return [[]];
        }

        $combinator = function (array $elements, int $k, int $start, array $combination) use (
            &$combinator,
            &$results,
            $n,
            $r
        ): void {
            if (count($combination) === $r) {
                $results[] = $combination;

                return;
            }

            if ($n - $start < $r - count($combination)) {
                return;
            }

            for ($i = $start; $i < $n; $i++) {
                $next = array_merge($combination, [$elements[$i]]);

                $combinator($elements, $r, $i + 1, $next);
            }
        };

        $combinator($iterable, $r, 0, []);

        return $results;
    }

    private static function solve(array $columns, array $targets, array $buttons): ?int
    {
        $vars = count($columns);
        $rows = count($targets);

        $bounds = [];

        for ($i = 0; $i < $vars; $i++) {
            $target = PHP_INT_MAX;

            foreach ($buttons[$i] as $button) {
                if ($targets[$button] < $target) {
                    $target = $targets[$button];
                }
            }

            $bounds[$i] = $target;
        }

        $matrix = [];

        for ($r = 0; $r < $rows; $r++) {
            $row = [];

            for ($c = 0; $c < $vars; $c++) {
                $row[] = $columns[$c][$r];
            }

            $row[]    = floatval($targets[$r]);
            $matrix[] = $row;
        }

        $row     = 0;
        $cols    = array_fill(0, $vars, -1);
        $epsilon = 1e-4;

        for ($c = 0; $c < $vars && $row < $rows; $c++) {
            $sel = -1;
            for ($r = $row; $r < $rows; $r++) {
                if (abs($matrix[$r][$c]) > $epsilon) {
                    $sel = $r;
                    break;
                }
            }

            if ($sel === -1) {
                continue;
            }

            if ($sel !== $row) {
                $temp         = $matrix[$row];
                $matrix[$row] = $matrix[$sel];
                $matrix[$sel] = $temp;
            }

            $pivotVal = $matrix[$row][$c];
            for ($j = $c; $j <= $vars; $j++) {
                $matrix[$row][$j] /= $pivotVal;
            }

            $cols[$c] = $row;

            for ($r = 0; $r < $rows; $r++) {
                if ($r !== $row && abs($matrix[$r][$c]) > $epsilon) {
                    $factor = $matrix[$r][$c];
                    for ($j = $c; $j <= $vars; $j++) {
                        $matrix[$r][$j] -= $factor * $matrix[$row][$j];
                    }
                }
            }

            $row++;
        }

        for ($r = $row; $r < $rows; $r++) {
            if (abs($matrix[$r][$vars]) > $epsilon) {
                return null;
            }
        }

        $free      = [];
        $dependent = [];

        for ($c = 0; $c < $vars; $c++) {
            if ($cols[$c] !== -1) {
                $dependent[$c] = $cols[$c];
            } else {
                $free[] = $c;
            }
        }

        $best = null;
        self::search($free, 0, [], $matrix, $dependent, $vars, $best, $bounds);

        return $best;
    }

    private static function search(
        array $free,
        int $idx,
        array $values,
        array $matrix,
        array $dependent,
        int $vars,
        ?int &$best,
        array $bounds
    ): void {
        if ($idx >= count($free)) {
            $current = 0;
            foreach ($values as $val) {
                $current += $val;
            }

            foreach ($dependent as $r => $ridx) {
                $val = $matrix[$ridx][$vars];

                foreach ($free as $f => $fidx) {
                    $val -= $matrix[$ridx][$fidx] * $values[$f];
                }

                if ($val < -1e-4) {
                    return;
                }

                if (abs($val - round($val)) > 1e-4) {
                    return;
                }

                $intVal = round($val) |> intval(...);

                if ($intVal > $bounds[$r]) {
                    return;
                }

                $current += $intVal;
            }

            if ($best === null || $current < $best) {
                $best = $current;
            }

            return;
        }

        $fVarIdx = $free[$idx];

        $limit = $bounds[$fVarIdx];

        for ($v = 0; $v <= $limit; $v++) {
            $next   = $values;
            $next[] = $v;

            self::search($free, $idx + 1, $next, $matrix, $dependent, $vars, $best, $bounds);
        }
    }
}