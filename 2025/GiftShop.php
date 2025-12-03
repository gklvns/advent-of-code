<?php

declare(strict_types=1);

namespace AdventOfCode\Year2025;

use AdventOfCode\File;
use AdventOfCode\Task;

final class GiftShop extends Task
{
    private static array $input = [];

    public static function run(string $file): void
    {
        $input = File::contents($file);

        self::$input = array_map(
            static fn (string $range) => explode('-', $range),
            $input |> (static fn (string $input) => explode(',', $input))
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
        $count = 0;

        foreach (self::$input as $range) {
            [$start, $end] = array_map('intval', $range);

            $number = $start;

            while ($number <= $end) {
                $candidate = min(self::nextRepeatNumber($number), self::nextRepeatSequence($number));
                if ($candidate > $end) {
                    break;
                }

                $string = strval($candidate);
                $half   = intval(strlen($string) / 2);

                if (substr($string, 0, $half) === substr($string, $half)) {
                    $count += $candidate;
                }

                $number = $candidate + 1;
            }
        }

        self::result($count);
    }

    private static function countIdsWithAnyRepeatedSequence(): void
    {
        $count = 0;

        foreach (self::$input as $range) {
            [$start, $end] = array_map('intval', $range);

            $number = $start;

            while ($number <= $end) {
                $candidate = min(self::nextRepeatNumber($number), self::nextRepeatSequence($number));
                if ($candidate > $end) {
                    break;
                }

                if (self::isSpecialNumber($candidate)) {
                    $count += $candidate;
                }

                $number = $candidate + 1;
            }
        }

        self::result($count);
    }

    public static function isSpecialNumber(int $number): bool
    {
        if ($number < 10) {
            return false;
        }

        $string = strval($number);
        $length = strlen($string);

        if (preg_match('/^(.)\1+$/', $string)) {
            return true;
        }

        for ($sub = 1; $sub <= $length / 2; $sub++) {
            if ($length % $sub === 0) {
                $pattern  = substr($string, 0, $sub);
                $repeated = str_repeat($pattern, $length / $sub);

                if ($string === $repeated) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function nextRepeatNumber(int $number): int
    {
        if ($number < 10) {
            return $number;
        }

        $string    = strval($number);
        $length    = strlen($string);
        $first     = intval($string[0]);
        $candidate = $first
                |> strval(...)
                |> (static fn (string $first) => str_repeat($first, $length))
                |> intval(...);

        if ($candidate >= $number) {
            return $candidate;
        }

        $next = $first + 1;
        if ($next <= 9) {
            return $next
                    |> strval(...)
                    |> (static fn (string $next) => str_repeat($next, $length))
                    |> intval(...);
        }

        return $length + 1
                |> (static fn (int $number) => str_repeat('1', $number))
                |> intval(...);
    }

    public static function nextRepeatSequence(int $number): int
    {
        if ($number < 10) {
            return $number;
        }

        $string = strval($number);
        $length = strlen($string);
        $next   = PHP_INT_MAX;

        for ($target = $length; $target <= $length + 1; $target++) {
            for ($sub = 1; $sub <= floor($target / 2); $sub++) {
                if ($target % $sub !== 0) {
                    continue;
                }

                if ($target > $length) {
                    $pattern = str_repeat('1', $sub);
                } else {
                    $pattern = substr($string, 0, $sub);
                }

                $candidate       = str_repeat($pattern, $target / $sub);
                $candidateNumber = intval($candidate);

                if ($candidateNumber >= $number && $candidateNumber < $next && strlen($candidate) == $target) {
                    $next = $candidateNumber;
                }

                $nextPattern = strval(intval($pattern) + 1);

                if (strlen($nextPattern) > $sub) {
                    continue;
                }

                $candidate       = str_repeat($nextPattern, $target / $sub);
                $candidateNumber = intval($candidate);

                if ($candidateNumber >= $number && $candidateNumber < $next && strlen($candidate) == $target) {
                    $next = $candidateNumber;
                }
            }
        }

        return $next;
    }
}
