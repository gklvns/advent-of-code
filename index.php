<?php

use AdventOfCode\AdventOfCode;
use AdventOfCode\Year2025\Cafeteria;
use AdventOfCode\Year2025\Factory;
use AdventOfCode\Year2025\GiftShop;
use AdventOfCode\Year2025\Laboratories;
use AdventOfCode\Year2025\Lobby;
use AdventOfCode\Year2025\MovieTheater;
use AdventOfCode\Year2025\Playground;
use AdventOfCode\Year2025\PrintingDepartment;
use AdventOfCode\Year2025\Reactor;
use AdventOfCode\Year2025\SecretEntrance;
use AdventOfCode\Year2025\TrashCompactor;

require_once __DIR__ . '/vendor/autoload.php';

$adventOfCode = new AdventOfCode();

// 2025
$adventOfCode->tasks(2025, [
    SecretEntrance::class,
    GiftShop::class,
    Lobby::class,
    PrintingDepartment::class,
    Cafeteria::class,
    TrashCompactor::class,
    Laboratories::class,
    Playground::class,
    MovieTheater::class,
    Factory::class,
    Reactor::class,
]);

$adventOfCode->run(2025, 'input.txt');