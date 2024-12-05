<?php

//class Day01 implements Day
//{
//}

$lines = explode("\n", file_get_contents("day01.txt"));

$left = [];
$right = [];
foreach ($lines as $line) {
	$chunks = array_values(array_filter(explode(" ", $line)));
	if (count($chunks) != 2) {
		continue;
	}

	$left[] = $chunks[0];
	$right[] = $chunks[1];
}

if (count($left) != count($right)) {
	echo "Sizes don't match\n";
	die();
}

$total = 0;
sort($left);
sort($right);

for ($i = 0; $i < count($left); $i++) {
	$total += abs($left[$i] - $right[$i]);
}

echo "part 1: {$total}\n";

$rightIndexed = [];
foreach ($right as $n) {
	if (empty($rightIndexed[$n])) {
		$rightIndexed[$n] = 0;
	}
	$rightIndexed[$n]++;
}

$total = 0;
foreach ($left as $n) {
	$rightTimes = $rightIndexed[$n] ?? 0;
	$total += $rightTimes * $n;
}

echo "part 2: {$total}\n";


