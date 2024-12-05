<?php

include(__DIR__ . "/AdventOfCode.php");
include(__DIR__ . "/Day.php");

if ($argc != 3) {
    echo "Usage: php run.php [year] [day]\n";
    exit();
}

array_shift($argv);

[ $year, $day ] = $argv;

$aoc = new AdventOfCode();

