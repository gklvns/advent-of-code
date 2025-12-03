<?php

use AdventOfCode\AdventOfCode;
use AdventOfCode\TwentyFive\Cafeteria;
use AdventOfCode\TwentyFive\GiftShop;
use AdventOfCode\TwentyFive\Lobby;
use AdventOfCode\TwentyFive\PrintingDepartment;
use AdventOfCode\TwentyFive\SecretEntrance;

require_once __DIR__ . '/vendor/autoload.php';

$adventOfCode = new AdventOfCode();

// 2025
$adventOfCode->tasks(2025, [
    SecretEntrance::class,
    GiftShop::class,
    Lobby::class,
    PrintingDepartment::class,
    Cafeteria::class,
]);

// $file = 'example.txt';
$file = 'input.txt';

$adventOfCode->run(2025, $file);
