<?php

class Day06 extends Day
{
	protected $grid = [];

	public function part1()
	{
		$this->echoOn();

		$x = 0;
		$y = 0;

		$lines = explode("\n", $this->data);
		foreach ($lines as $line) {
			if (strpos($line, '^') !== false) {
				// guard position
				$x = strpos($line, '^');
				$y = count($this->grid);
			}
			$this->grid[] = str_split($line);
		}

		$this->grid[$y][$x] = '.';

		$walked = [
			"{$x}-{$y}" => true,
		];

		$guard = [
			'x' => $x,
			'y' => $y,
			'dirX' => 0,
			'dirY' => -1,
		];

		while (1) {
			[ $newX, $newY ] = $this->getNextTile($guard);

			// guard has left (assume this always happens)
			if ($this->isOutOfBounds($newX, $newY)) {
				break;
			}

			if ($this->canWalk($newX, $newY)) {
				// if we're unblocked then just walk
				$guard['x'] = $newX;
				$guard['y'] = $newY;

				$walked["{$newX}-{$newY}"] = true;

			} else {
				// otherwise turn right
				if ($guard['dirY'] != 0) {
					// y:-1(up) -> x:1(right)
					// y:1(down) -> x:-1(left)
					$guard['dirX'] = $guard['dirY'] * -1;
					$guard['dirY'] = 0;
				} else if ($guard['dirX'] != 0) {
					// x:1(right) -> y:1(down)
					// x-1(left) -> y:-1(up)
					$guard['dirY'] = $guard['dirX'];
					$guard['dirX'] = 0;
				}
			}
		}

		return count($walked);
	}

	protected function getNextTile($guard)
	{
		$newX = $guard['x'] + $guard['dirX'];
		$newY = $guard['y'] + $guard['dirY'];

		return [ $newX, $newY ];
	}

	protected function isOutOfBounds($x, $y)
	{
		if (!isset($this->grid[$y]) || !isset($this->grid[$y][$x])) {
			return true;
		}

		return false;
	}

	protected function canWalk($x, $y)
	{
		return $this->grid[$y][$x] == '.';
	}

	public function part2()
	{
		return 0;
	}
}
