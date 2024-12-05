<?php

class Day01 extends Day
{
	protected $left = [];
	protected $right = [];

	public function part1()
	{
		$lines = explode("\n", $this->data);

		foreach ($lines as $line) {
			$chunks = array_values(array_filter(explode(" ", $line)));
			if (count($chunks) != 2) {
				continue;
			}

			$this->left[] = $chunks[0];
			$this->right[] = $chunks[1];
		}

		if (count($this->left) != count($this->right)) {
			throw new Exception("Sizes don't match");
		}

		$total = 0;
		sort($this->left);
		sort($this->right);

		for ($i = 0; $i < count($this->left); $i++) {
			$total += abs($this->left[$i] - $this->right[$i]);
		}

		return $total;
	}

	public function part2()
	{
		$rightIndexed = [];
		foreach ($this->right as $n) {
			if (empty($rightIndexed[$n])) {
				$rightIndexed[$n] = 0;
			}
			$rightIndexed[$n]++;
		}

		$total = 0;
		foreach ($this->left as $n) {
			$rightTimes = $rightIndexed[$n] ?? 0;
			$total += $rightTimes * $n;
		}

		return $total;
	}
}
