<?php

class Day03 extends Day
{
	public function part1()
	{
		$matches = [];

		preg_match_all('/mul\([0-9]+?,[0-9]+?\)/', $this->data, $matches);
		$total = 0;
		foreach ($matches[0] as $match) {
			[ $x, $y ] = explode(',', trim($match, 'mul()'));	
			$total += $x * $y;
		}

		return $total;
	}

	public function part2()
	{
		$matches = [];

		preg_match_all('/mul\([0-9]+?,[0-9]+?\)|do\(\)|don\'t\(\)/', $this->data, $matches);

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

		return $total;
	}
}
