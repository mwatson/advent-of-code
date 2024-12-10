<?php

class Day10 extends Day
{
	protected $map = [];

	protected $width = 0;
	protected $height = 0;

	protected $trailheads = [];
	protected $peaks = [];

    public function part1()
    {
    	$this->buildMap();

    	$score = 0;
    	foreach ($this->trailheads as [ 'x' => $x, 'y' => $y ]) {
    		$this->peaks = [];
    		$pathScore = $this->walkPath($x, $y, 0, 0);
    		//echo "PATHSCORE {$pathScore}\n===\n";
    		$score += $pathScore;
    	}

        return $score;
    }

    public function part2()
    {
    	$score = 0;
    	foreach ($this->trailheads as [ 'x' => $x, 'y' => $y ]) {
    		$this->peaks = [];
    		$pathScore = $this->walkPath($x, $y, 0, 0, false);
    		$score += $pathScore;
    	}

        return $score;
    }

    protected function buildMap()
    {
    	$this->map = [];
		$lines = explode("\n", $this->data);

		foreach ($lines as $y => $line) {
			$row = array_map('intval', str_split($line));

			foreach ($row as $x => $height) {
				if ($height === 0) {
					$this->trailheads[] = [ 'x' => $x, 'y' => $y ];
				}
			}

			$this->map[] = $row;
		}

		$this->width = count($this->map[0]);
		$this->height = count($this->map);
    }

    protected function walkPath($x, $y, $score, $depth, $trackPeaks = true)
    {
    	$search = [
    		[  0, -1 ],
    		[  1,  0 ],
    		[  0,  1 ],
    		[ -1,  0 ],
    	];

    	$val = $this->map[$y][$x];


    	//echo str_pad("", $depth, "\t");
    	//echo " ({$x}, {$y}):{$val}\n";


    	foreach ($search as [ $xD, $yD ]) {
    		$newX = $x + $xD;
    		$newY = $y + $yD;

    		// off the map, skip
    		if ($newX < 0 || $newX >= $this->width) {
    			continue;
    		}
    		if ($newY < 0 || $newY >= $this->height) {
    			continue;
    		}

    		if ($this->map[$newY][$newX] !== $val + 1) {
    			continue;
    		}
    		
			if ($this->map[$newY][$newX] === 9) {
				if (empty($this->peaks["{$newX}-{$newY}"])) {
					if ($trackPeaks) {
						$this->peaks["{$newX}-{$newY}"] = true;
					}
					$score++;
				}
			}

			$score = $this->walkPath($newX, $newY, $score, $depth + 1, $trackPeaks);
    	}

    	return $score;
    }
}
