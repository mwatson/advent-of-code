<?php

$data = file_get_contents("day03.txt");

$matches = [];

preg_match_all('/mul\([0-9]+?,[0-9]+?\)/', $data, $matches);
$total = 0;
foreach ($matches[0] as $match) {
	[ $x, $y ] = explode(',', trim($match, 'mul()'));	
	$total += $x * $y;
}

echo "Part 1: {$total}\n";

preg_match_all('/mul\([0-9]+?,[0-9]+?\)|do\(\)|don\'t\(\)/', $data, $matches);

$total = 0;
$run = true;
foreach ($matches[0] as $match) {

	if ($match == "do()") {
		$run = true;
		continue;
	}
	if ($match == "don't()") {
		$run = false;
		continue;
	}

	if (!$run) {
		continue;
	}

	
	[ $x, $y ] = explode(',', trim($match, 'mul()'));	
	$total += $x * $y;
}

echo "Part 2: {$total}\n";
