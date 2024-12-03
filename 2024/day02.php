<?php

$lines = explode("\n", file_get_contents("day02.txt"));
//$lines = [ "62 61 62 63 65 67 68 71" ];

$safeCount = 0;
$fixedCount = 0;
foreach ($lines as $line) {
	$data = array_filter(explode(" ", $line));

	if (empty($data)) {
		continue;
	}

	$safeID = safeReport($data);
	if ($safeID == -1) {
		$safeCount++;
	} else {
		$fixFound = false;
		for ($i = 0; $i < count($data); $i++) {
			if (safeReport($data, $i) == -1) {
				//echo implode(" ", $data) . " passed!\n";
				$fixedCount++;
				$fixFound = true;
				break;
			}
		}

		if (!$fixFound) {
			echo "still broken: " . implode(' ', $data) . "\n";
		}
	}
}

function logger($line) {
	//echo $line;
}

//$fixedCount = $fixedCount $safeCount;

echo "part 1: {$safeCount}\n";
echo "part 2: {$fixedCount}\n";

function safeReport(array $report, int $skipId = -1) : int
{
	$rep = array_merge([], $report);

	if ($skipId > -1) {
		unset($rep[$skipId]);
		$rep = array_values($rep);
	}

	if ($skipId != -1) {
		logger("Testing " . implode(' ', $report) . " -> " . implode(' ', $rep) . " ");
	}

	$reportDir = false;
	for ($i = 0; $i < count($rep) - 1; $i++) {
		$j = $i + 1;

		$diff = $rep[$i] - $rep[$j];

		if ($diff > 3 || $diff < -3) {
			logger("failed [{$i}] (> 3) ({$rep[$i]} - {$rep[$j]})\n");
			return $i;
		}

		$dir = 0;
		if ($diff > 0) {
			$dir = 1;
		} else if ($diff < 0) {
			$dir = -1;
		}

		if ($dir === 0) {
			logger("failed [{$i}] (no dir)\n");
			return $i;
		}

		if ($reportDir === false) {
			$reportDir = $dir;
			continue;
		}

		if ($reportDir !== $dir) {
			logger("failed [{$i}] (change dir)\n");
			return $i;
		}
	}

	return -1;
}
