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

// RRRR   2 top, 3 bottom, 3 left, 2 right = 10
// RRRR
//   RRR
//   R

/*
......CC    top: 5, bottom: 6, left: 5, right: 6 = 22
......CCC
.....CC
...CCC
....C
....CC
.....C

........FF  top: 2, bottom: 4, left: 4, right 2 = 12
.........F
.......FFF
.......FFF
........F
*/

		$grid = $this->getGrid();

		$cost = 0;

		for ($y = 0; $y < count($grid); $y++) {
			for ($x = 0; $x < count($grid[$y]); $x++) {
                $og = $grid[$y][$x];
				if ($grid[$y][$x] == '.') {
					continue;
				}

				$result = $this->findAdjacent($grid, $x, $y, [ "{$x}-{$y}" => 0 ]);

				$totalSides = 0;

                $xBlocks = [];
                $yBlocks = [];
				foreach ($result as $idx => $sides) {
					[ $xIdx, $yIdx ] = explode("-", $idx);
                    $yBlocks[$yIdx][$xIdx] = true;
                    $xBlocks[$xIdx][$yIdx] = true;

					// still do this so we can process subsequent pieces
					$grid[$yIdx][$xIdx] = ".";
				}

                ksort($xBlocks);
                foreach ($xBlocks as $bx => $col) {
                    ksort($xBlocks[$bx]);
                }

                ksort($yBlocks);
                foreach ($yBlocks as $by => $row) {
                    ksort($yBlocks[$by]);
                }

                $totalEdges = 0;

                $topScan = 0;
                $botScan = 0;

                // scan down: 
                // * count groups of adjacent blocks w/no block above (y = -1)
                // scan up:
                // * count groups of adjacent blocks w/no block below (y = +1)
                foreach ($yBlocks as $yB => $row) {
                    $lastDown = -1;
                    $lastUp = -1;
                    $borders = 0;

                    foreach ($row as $xB => $v) {
                        // scan down
                        if (empty($yBlocks[$yB - 1][$xB])) {
                            if ($lastDown == -1) {
                                $borders++;
                                $topScan++;
                                $lastDown = $xB;
                            } else if ($lastDown + 1 != $xB) {
                                $lastDown = -1;
                            } else {
                                $lastDown = $xB;
                            }
                        } else {
                            $lastDown = -1;
                        }

                        // scan up
                        if (empty($yBlocks[$yB + 1][$xB])) {
                            //echo "VALID BLOCK {$xB}, {$yB} [{$lastUp}]\n";
                            if ($lastUp == -1) {
                                $borders++;
                                $botScan++;
                                //echo "BORDER++ ({$xB}, {$yB})\n";
                                $lastUp = $xB;
                            } else if ($lastUp - 1 != $xB) {
                                $lastUp = -1;
                            } else {
                                $lastUp = $xB;
                            }
                        } else {
                            $lastUp = -1;
                        }
                    }

                    $totalEdges += $borders;
                }

                //echo "TOP: {$topScan}, BOT: {$botScan}\n";

                $rScan = 0;
                $lScan = 0;

                // scan right:
                // * count groups of adjacent blocks w/no block to left (x = -1)
                // scan left:
                // * count groups of adjacent blocks w/no block to right (x = +1)
                foreach ($xBlocks as $xB => $col) {
                    $lastRight = -1;
                    $lastLeft = -1;
                    $borders = 0;
                    foreach ($col as $yB => $v) {
                        // scan right
                        if (empty($xBlocks[$xB - 1][$yB])) {
                            if ($lastRight == -1) {
                                $borders++;
                                $rScan++;
                                $lastRight = $yB;
                            } else if ($lastRight + 1 != $yB) {
                                $lastRight = -1;
                            } else {
                                $lastRight = $yB;
                            }
                        } else {
                            $lastRight = -1;
                        }

                        // scan left
                        if (empty($xBlocks[$xB + 1][$yB])) {
                            if ($lastLeft == -1) {
                                $borders++;
                                $lScan++;
                                $lastLeft = $yB;
                            } else if ($lastLeft - 1 != $yB) {
                                $lastLeft = -1;
                            } else {
                                $lastLeft = $yB;
                            }
                        } else {
                            $lastLeft = -1;
                        }
                    }

                    //echo "L/R {$borders}\n";

                    $totalEdges += $borders;
                }

                echo "L: {$lScan}, R: {$rScan} ({$og})\n";

                $this->echo("Edges {$og}: {$totalEdges}\n");

				$cost += count($result) * $totalEdges;

                //$this->printGrid($grid);
			}
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
