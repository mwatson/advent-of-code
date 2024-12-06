<?php

class Day06 extends Day
{
	protected $grid = [];
	protected $walked = [];

	protected $guardStart = [
		'x' => 0,
		'y' => 0,
	];

	public function part1()
	{
		$this->echoOn();

		$lines = explode("\n", $this->data);
		foreach ($lines as $line) {
			if (strpos($line, '^') !== false) {
				// guard position
				$this->guardStart['x'] = strpos($line, '^');
				$this->guardStart['y'] = count($this->grid);
			}
			$this->grid[] = str_split($line);
		}

		[ 'x' => $x, 'y' => $y ] = $this->guardStart;

		$this->grid[$y][$x] = '.';

		$this->walked[$y][$x] = 1;

		$guard = [
			'x' => $x,
			'y' => $y,
			'dirX' => 0,
			'dirY' => -1,
		];

		$this->walkTheWalk($guard);

		$walked = 0;
		foreach ($this->walked as $walkY) {
			$walked += count($walkY);
		}

		//print_r($this->walked);

		return $walked;
	}

	public function part2()
	{
		$guard = [
			'x' => $this->guardStart['x'],
			'y' => $this->guardStart['y'],
			'dirX' => 0,
			'dirY' => -1,
		];

		$loopers = 0;

		foreach ($this->walked as $y => $walkRow) {
			foreach ($walkRow as $x => $times) {
				$this->grid[$y][$x] = '#';

				if (!$this->walkTheWalk($guard, false)) {
					$loopers++;
				}

				$this->grid[$y][$x] = '.';
			}
		}

		return $loopers;
	}

	protected function walkTheWalk($guard, $trackWalks = true)
	{
		// this is stupid
		$loops = 7000;

		while ($loops--) {
			[ $newX, $newY ] = $this->getNextTile($guard);

			// guard has left
			if ($this->isOutOfBounds($newX, $newY)) {
				return true;
			}

			if ($this->canWalk($newX, $newY)) {
				// if we're unblocked then just walk
				$guard['x'] = $newX;
				$guard['y'] = $newY;

				if ($trackWalks) {
					if (isset($this->walked[$newY][$newX])) {
						$this->walked[$newY][$newX]++;
					} else {
						$this->walked[$newY][$newX] = 1;
					}
				}

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

		// we never exited
		return false;
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
}
