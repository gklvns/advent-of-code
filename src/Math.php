<?php

declare(strict_types=1);

namespace AdventOfCode;

use DivisionByZeroError;

final readonly class Math
{
    /**
     * Python's implementation of divmod.
     */
    public static function divmod(int $dividend, int $divisor): array
    {
        if ($divisor === 0) {
            throw new DivisionByZeroError('Division by zero in divmod.');
        }

        $div = intval($dividend < 0 ? ceil($dividend / $divisor) - 1 : floor($dividend / $divisor));
        $mod = self::mod($dividend, $divisor);

        return [$div, $mod];
    }

    /**
     * Python's implementation of modulo.
     */
    public static function mod(int $dividend, int $divisor): int
    {
        return intval($dividend - floor($dividend / $divisor) * $divisor);
    }
}
