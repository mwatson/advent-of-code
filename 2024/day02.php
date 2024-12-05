<?php

class Day02 extends Day
{
	protected $fixedCount = 0;

	public function part1()
	{
		$lines = explode("\n", $this->data);
		//$lines = [ "62 61 62 63 65 67 68 71" ];

		//$this->echoOn();

		$safeCount = 0;
		foreach ($lines as $line) {
			$data = array_filter(explode(" ", $line));

			if (empty($data)) {
				continue;
			}

			$safeID = $this->safeReport($data);
			if ($safeID == -1) {
				$safeCount++;
			} else {
				$fixFound = false;
				for ($i = 0; $i < count($data); $i++) {
					if ($this->safeReport($data, $i) == -1) {
						//echo implode(" ", $data) . " passed!\n";
						$this->fixedCount++;
						$fixFound = true;
						break;
					}
				}

				if (!$fixFound) {
					$this->echo("still broken: " . implode(' ', $data));
				}
			}
		}

		return $safeCount;
	}

	public function part2()
	{
		return $this->fixedCount;
	}

	protected function safeReport(array $report, int $skipId = -1) : int
	{
		$rep = array_merge([], $report);

		if ($skipId > -1) {
			unset($rep[$skipId]);
			$rep = array_values($rep);
		}

		if ($skipId != -1) {
			$this->echo("Testing " . implode(' ', $report) . " -> " . implode(' ', $rep) . " ");
		}

		$reportDir = false;
		for ($i = 0; $i < count($rep) - 1; $i++) {
			$j = $i + 1;

			$diff = $rep[$i] - $rep[$j];

			if ($diff > 3 || $diff < -3) {
				$this->echo("failed [{$i}] (> 3) ({$rep[$i]} - {$rep[$j]})\n");
				return $i;
			}

			$dir = 0;
			if ($diff > 0) {
				$dir = 1;
			} else if ($diff < 0) {
				$dir = -1;
			}

			if ($dir === 0) {
				$this->echo("failed [{$i}] (no dir)\n");
				return $i;
			}

			if ($reportDir === false) {
				$reportDir = $dir;
				continue;
			}

			if ($reportDir !== $dir) {
				$this->echo("failed [{$i}] (change dir)\n");
				return $i;
			}
		}

		return -1;
	}
}
