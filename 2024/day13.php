<?php

class Day13 extends Day
{
    protected $pos = 0;

    public function part1()
    {
    	/*
    	$this->data = "Button A: X+94, Y+34
Button B: X+22, Y+67
Prize: X=8400, Y=5400

Button A: X+26, Y+66
Button B: X+67, Y+21
Prize: X=12748, Y=12176

Button A: X+17, Y+86
Button B: X+84, Y+37
Prize: X=7870, Y=6450

Button A: X+69, Y+23
Button B: X+27, Y+71
Prize: X=18641, Y=10279";
*/

		$tokens = 0;

		while ($machine = $this->getNextMachine()) {

			// A = 3 token
			// B = 1 token

			//print_r($machine);

			$max = [ 'a' => 0, 'b' => 0 ];
			foreach ([ 'a', 'b' ] as $ab) {
				$xMax = floor($machine['p']['x'] / $machine[$ab]['x']);
				$yMax = floor($machine['p']['y'] / $machine[$ab]['y']);

				if ($yMax < $xMax) {
					$max[$ab] = $yMax;
				} else {
					$max[$ab] = $xMax;
				}

				//echo "{$ab} x: {$xMax}\n";
				//echo "{$ab} y: {$yMax}\n";
			}

			// $max represents the max # of times you can press each button without moving too far
			//print_r($max);

			$pref = 'b';
			if ($max['b'] / 3 > $max['a']) {
				$pref = 'a';
			}

			$other = $pref == 'a' ? 'b' : 'a';

			// distance the claw will move when pressing B the max # of times
			// TODO: A should be given preference if B requires >3x presses
			$distX = $max[$pref] * $machine[$pref]['x'];
			$distY = $max[$pref] * $machine[$pref]['y'];

			// distance left
			$xDiff = $machine['p']['x'] - $distX;
			$yDiff = $machine['p']['y'] - $distY;

			// in order for the machine to be completable, the distance has to be divisible by the a button distance
			while ($xDiff % $machine[$other]['x'] != 0) {
				$xDiff += $machine[$pref]['x'];
				$distX -= $machine[$pref]['x'];

				if ($distX <= 0) {
					break;
				}
		    }

		    while ($yDiff % $machine[$other]['y'] != 0) {
				$yDiff += $machine[$pref]['y'];
				$distY -= $machine[$pref]['y'];

				if ($distY <= 0) {
					break;
				}
			}

			if ($distX <= 0 || $distY <= 0) {
				echo "Nope, this machine is not solvable :(\n";
				continue;
			} else {
				$aPresses = 0;
				if ($xDiff / $machine[$other]['x'] > $yDiff / $machine[$other]['y']) {
					$aPresses = $xDiff / $machine['a']['x'];
				} else {
					$aPresses = $yDiff / $machine['a']['y'];
				}

				$xDist = $machine[$other]['x'] * $aPresses;
				$yDist = $machine[$other]['y'] * $aPresses;

				$bPressesX = ($machine['p']['x'] - $xDist) / $machine[$pref]['x'];
				$bPressesY = ($machine['p']['y'] - $yDist) / $machine[$pref]['y'];

				if ($bPressesX != $bPressesY) {
					echo "Sorry, this machine is not solvable :(\n";
					continue;
				}

				$tokens += $aPresses * ($pref == 'a' ? 3 : 1);
				$tokens += $bPressesX * ($pref == 'b' ? 3 : 1);
			}
		}

		return $tokens;
	}

	public function part2()
	{
	}

    protected function getNextMachine()
    {
        if (!is_array($this->data)) {
            $this->data = explode("\n", $this->data);
        }

        if ($this->pos >= count($this->data)) {
            return false;
        }

        $info = array_slice($this->data, $this->pos, 3);
        $this->pos += 4;

        $machine = [
            'a' => [ 'x' => 0, 'y' => 0 ],
            'b' => [ 'x' => 0, 'y' => 0 ],
            'p' => [ 'x' => 0, 'y' => 0 ],
        ];

        $matches = [];
        foreach ($info as $inf) {
            if (preg_match('/^Button (A|B): X\+([0-9]+), Y\+([0-9]+)$/', $inf, $matches)) {
                $button = strtolower($matches[1]);
                $machine[$button]['x'] = $matches[2];
                $machine[$button]['y'] = $matches[3];
            }
            if (preg_match('/^Prize: X=([0-9]+), Y=([0-9]+)$/', $inf, $matches)) {
            	$machine['p']['x'] = $matches[1];
            	$machine['p']['y'] = $matches[2];
            }
        }

        return $machine;
    }
}