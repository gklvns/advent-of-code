<?php

namespace AdventOfCode\Year2025;

use AdventOfCode\File;
use AdventOfCode\Task;

final class TrashCompactor extends Task
{
    private static array $input = [];

    public static function run(string $file): void
    {
        self::$input = File::lines($file);

        self::calculateGrandTotal();
        self::calculateCephalopodGrandTotal();
    }

    public static function day(): int
    {
        return 6;
    }

    private static function calculateGrandTotal(): void
    {
        $total     = 0;
        $equations = self::transpose(
            array_map(
                static fn (string $line) => $line
                        |> trim(...)
                        |> (static fn (string $line) => preg_replace('/\s+/', ' ', $line))
                        |> (static fn (string $line) => explode(' ', $line))
                        |> (static fn (array $numbers) => array_map('trim', $numbers)),
                self::$input
            )
        );

        foreach ($equations as $numbers) {
            $operator = array_pop($numbers);

            switch ($operator) {
                case '+':
                    $total += array_sum($numbers);
                    break;
                case '*':
                    $numbers  = array_filter($numbers, static fn (int $number) => $number > 0);
                    $multiply = array_shift($numbers);

                    foreach ($numbers as $number) {
                        $multiply *= $number;
                    }

                    $total += $multiply;
                    break;
            }
        }

        self::result($total);
    }

    private static function calculateCephalopodGrandTotal(): void
    {
        $columns = self::$input
                |> (static fn (array $lines) => array_map(static fn (string $line) => $line |> str_split(...), $lines))
                |> self::transpose(...);

        $groups = $group = [];

        foreach ($columns as $column) {
            if (array_filter($column, static fn (string $line) => $line !== ' ')) {
                $group[] = $column;

                continue;
            }

            $groups[] = $group;

            $group = [];
        }

        $groups[] = $group;

        $total = 0;

        foreach ($groups as $group) {
            $operator = array_pop($group[0]);

            $numbers = array_map(
                static fn (array $numbers) => $numbers
                        |> (static fn (array $numbers) => implode('', $numbers))
                        |> intval(...),
                $group
            );

            switch ($operator) {
                case '+':
                    $total += array_sum($numbers);
                    break;
                case '*':
                    $numbers  = array_filter($numbers, static fn (int $number) => $number > 0);
                    $multiply = array_shift($numbers);

                    foreach ($numbers as $number) {
                        $multiply *= $number;
                    }

                    $total += $multiply;
                    break;
            }
        }

        self::result($total);
    }

    private static function transpose(array $lines): array
    {
        $data    = [];
        $count   = count($lines);
        $columns = $count > 0 ? count($lines[0]) : 0;

        for ($i = 0; $i < $columns; $i++) {
            $row = [];

            for ($j = 0; $j < $count; $j++) {
                $row[] = $lines[$j][$i] ?? '';
            }

            $data[] = $row;
        }

        return $data;
    }
}