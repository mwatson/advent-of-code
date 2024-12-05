<?php

include(__DIR__ . "/AdventOfCode.php");
include(__DIR__ . "/Day.php");

if ($argc != 3) {
    echo "Usage: php run.php [year] [day]\n";
    exit();
}

array_shift($argv);

[ $year, $day ] = $argv;

try {
	$aoc = new AdventOfCode($year, $day);
	$aoc->exec();
	$aoc->displayResults();
} catch (Exception $e) {
	echo "AoC Error: {$e->getMessage()}\n";
	exit();
}
