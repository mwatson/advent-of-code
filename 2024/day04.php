<?php

$lines = explode("\n", file_get_contents(__DIR__ . "/day04.txt"));

$loopcount = 0;

foreach ($lines as $i => $line) {
	$lines[$i] = str_split($line);
}

$width = count($lines[0]);
$height = count($lines);

$directions = [
	"up",
	"upright",
	"right",
	"downright",
	"down",
	"downleft",
	"left",
	"upleft",
];

function searchWord($lines, $x, $y, $lookingFor, $dir) {
	// there are so many better ways to do this
	$move = [ 'x' => 0, 'y' => 0 ];
	if ($dir == "up") {
		$move['y'] = -1;
	} else if ($dir == "upright") {
		$move['x'] = 1;
		$move['y'] = -1;
	} else if ($dir == "right") {
		$move['x'] = 1;
	} else if ($dir == "downright") {
		$move['x'] = 1;
		$move['y'] = 1;
	} else if ($dir == "down") {
		$move['y'] = 1;
	} else if ($dir == "downleft") {
		$move['x'] = -1;
		$move['y'] = 1;
	} else if ($dir == "left") {
		$move['x'] = -1;
	} else if ($dir == "upleft") {
		$move['x'] = -1;
		$move['y'] = -1;
	}

	$x += $move['x'];
	$y += $move['y'];

	if (empty($lines[$y]) || empty($lines[$y][$x])) {
		return false;
	}

	if ($lines[$y][$x] == $lookingFor) {
		$nextLetter = "";
		switch ($lookingFor) {
			case "M":
				$nextLetter = "A";
				break;
			case "A":
				$nextLetter = "S";
				break;
			case "S":
				return true;
		}

		return searchWord($lines, $x, $y, $nextLetter, $dir);
	}

	return false;
}

$st = microtime(true);

$wordCount = 0;
foreach ($lines as $y => $line) {
	foreach ($line as $x => $char) {
		if ($char == "X") {
			foreach ($directions as $dir) {
				if (searchWord($lines, $x, $y, "M", $dir)) {
					$wordCount++;
				}
			}
		}
	}
}

$en = microtime(true);

echo "Part 1: {$wordCount}\n";
echo "Runtime " . $en - $st . "s\n";

function findXmas($lines, $x, $y, $col) {
	/*
	only possible combos:
	M.M    M.S    S.M    S.S
	.A.    .A.    .A.    .A.
	S.S    M.S    S.M    M.M
	*/

	$search = [
		[ 'x' => 0, 'y' => 0, 'search' => [ 'M', 'M', 'S', 'S' ], ],
		[ 'x' => 2, 'y' => 0, 'search' => [ 'M', 'S', 'M', 'S' ], ],
		[ 'x' => 1, 'y' => 1, 'search' => [ 'A', 'A', 'A', 'A' ], ],
		[ 'x' => 0, 'y' => 2, 'search' => [ 'S', 'M', 'S', 'M' ], ],
		[ 'x' => 2, 'y' => 2, 'search' => [ 'S', 'S', 'M', 'M' ], ],
	];

	$matches = 0;
	foreach ($search as $sinfo) {
		$xPos = $x + $sinfo['x'];
		$yPos = $y + $sinfo['y'];
		
		if ($lines[$yPos][$xPos] == $sinfo['search'][$col]) {
			$matches++;
		}
	}

	if ($matches == 5) {
		return true;
	}

	if ($col == 3) {
		return false;
	}

	return findXmas($lines, $x, $y, $col + 1);
}

$st = microtime(true);

$wordCount = 0;
for ($y = 0; $y < count($lines) - 2; $y++) {
	$line = $lines[$y];
	for ($x = 0; $x < count($line) - 2; $x++) {
		$char = $line[$x];
		if ($char != "M" && $char != "S") {
			continue;
		}

		if (findXmas($lines, $x, $y, 0)) {
			$wordCount++;
		}
	}
}

$en = microtime(true);

echo "Part 2: {$wordCount}\n";
echo "Runtime " . $en - $st . "s\n";

