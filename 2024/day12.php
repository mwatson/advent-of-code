<?php

class Day12 extends Day
{
    public function part1()
    {
		$this->echoOn();

		$grid = $this->getGrid();

		$cost = 0;

		for ($y = 0; $y < count($grid); $y++) {
			for ($x = 0; $x < count($grid[$y]); $x++) {
				if ($grid[$y][$x] == '.') {
					continue;
				}

				$result = $this->findAdjacent($grid, $x, $y, [ "{$x}-{$y}" => 0 ]);

				$totalSides = 0;
				foreach ($result as $idx => $sides) {
					[ $xIdx, $yIdx ] = explode("-", $idx);
					$grid[$yIdx][$xIdx] = ".";

					$totalSides += $sides;
				}

				$cost += count($result) * $totalSides;
			}
		}

    	return $cost;
    }

    public function part2()
    {
		$this->echoOn();

        $this->data = "RRRRIICCFF
RRRRIICCCF
VVRRRCCFFF
VVRCCCJFFF
VVVVCJJCFE
VVIVCCJJEE
VVIIICJJEE
MIIIIIJJEE
MIIISIJEEE
MMMISSJEEE";

		$grid = $this->getGrid();

		$cost = 0;

		for ($y = 0; $y < count($grid); $y++) {
			for ($x = 0; $x < count($grid[$y]); $x++) {
				if ($grid[$y][$x] == '.') {
					continue;
				}

				$result = $this->findAdjacent($grid, $x, $y, [ "{$x}-{$y}" => 0 ]);

				$totalSides = 0;

                $blocks = [];
				foreach ($result as $idx => $sides) {
					[ $xIdx, $yIdx ] = explode("-", $idx);
                    $blocks[$yIdx][$xIdx] = true;

					// still do this so we can process subsequent pieces
					$grid[$yIdx][$xIdx] = ".";
				}

                ksort($blocks);
                foreach ($blocks as $by => $row) {
                    ksort($blocks[$by]);
                }

                $topScan = 0;
                foreach ($blocks as $by => $row) {
                    $this->echo("[ " . implode(' ', $row) . " ]\n");
                    $edges = 0;
                    $lastX = -1;
                    foreach ($row as $bx => $b) {
                        if (!empty($blocks[$by - 1][$bx])) {
                            continue;
                        }
                        if ($lastX == -1) {
                            $edges++;
                            $lastX = $bx;
                        }
                        if ($lastX + 1 < $bx) {
                            $lastX = -1;
                        } else {
                            $lastX = $bx;
                        }
                    }

                    $this->echo("Edges: {$edges}\n");

                    // RRRR
                    // RRRR
                    //   RRR
                    //   R
                }

                //print_r($blocks);
                die;

				//$cost += count($result) * $totalSides;
			}

			//$this->printGrid($grid);
		}

    	return $cost;
    }

    protected function getGrid()
    {
		return array_map(function($line) {
			return str_split($line);
		}, explode("\n", $this->data));
    }

    protected function findAdjacent($grid, $x, $y, $found)
    {
    	$search = [
    		[  0, -1 ],
    		[  1,  0 ],
    		[  0,  1 ],
    		[ -1,  0 ],
    	];

    	$item = $grid[$y][$x];
    	$thisIdx = "{$x}-{$y}";

    	foreach ($search as [ $xD, $yD ]) {
    		$xC = $x + $xD;
    		$yC = $y + $yD;

    		// out of bounds
    		if (!isset($grid[$yC]) || !isset($grid[$yC][$xC])) {
    			$found[$thisIdx]++;
    			continue;
    		}

    		$foundIdx = "{$xC}-{$yC}";

    		// we already found this one
    		if (isset($found[$foundIdx])) {
    			continue;
    		}

    		if ($grid[$yC][$xC] == $item) {
    			$found[$foundIdx] = 0;
    			$found = array_merge($found, $this->findAdjacent($grid, $xC, $yC, $found));
    		} else {
    			$found[$thisIdx]++;
    		}
    	}

    	return $found;
    }

    protected function printGrid($grid)
    {
    	foreach ($grid as $row) {
    		$this->echo(implode('', $row) . "\n");
    	}
    }
}
