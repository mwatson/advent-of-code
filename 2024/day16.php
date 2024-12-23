<?php

class Day16 extends Day
{
    protected $map = [];

    protected $dir = [ 1, 0 ];
    protected $start = [];
    protected $goal = [];

    public function part1()
    {
        $this->initMap();

        $this->drawMap();

        $paths = $this->traverse($this->start, $this->goal, []);

        print_r($paths);

        $total = 0;

        return $total;
    }

    public function part2()
    {
        return 0;
    }

    protected function traverse($start, $goal, $h)
    {
        $search = [
            [  0, -1 ],
            [  1,  0 ],
            [  0,  1 ],
            [ -1,  0 ],
        ];

        $dist = [];
        $prev = [];
        $queue = [];
        foreach ($this->map as $y => $row) {
            foreach ($row as $x => $_) {
                $k = "{$x}-{$y}";
                $dist[$k] = 999999999;
                $prev[$k] = null;
                $queue[$k] = $k;
            }
        }

        while (count($queue)) {
            $shortest = -1;
            $shortItem = "";
            foreach ($queue as $item) {
                if ($shortest == -1 || $dist[$item] < $shortest) {
                    $shortest = $dist[$item];
                    $shortItem = $item;
                }
            }

            unset($queue[$item]);

            foreach ($search as [ $sX, $sY ]) {
                $xCheck = $x + $sX;
                $yCheck = $y + $sY;
                if (empty($this->map[$yCheck][$xCheck]) &&
                    $this->map[$yCheck][$xCheck] == '#'
                ) {

                }
            }
        }
    }

    protected function initMap()
    {
        $lines = explode("\n", $this->data);
        foreach ($lines as $line) {
            $rowInfo = str_split($line);
            foreach ($rowInfo as $i => $r) {
                if ($r == '.') {
                    //$rowInfo[$i] = 9999999999;

                }
            }
            if (strpos($line, "S") !== false) {
                $this->start = [ strpos($line, "S"), count($this->map) ];
                //$rowInfo[strpos($line, "S")] = 9999999999;
                $rowInfo[strpos($line, "S")] = '.';
            }
            if (strpos($line, "E") !== false) {
                $this->goal = [ strpos($line, "E"), count($this->map) ];
                //$rowInfo[strpos($line, "E")] = 0;
                $rowInfo[strpos($line, "E")] = '.';
            }
            $this->map[] = $rowInfo;
        }
    }

    // this is unused
    protected function scoreMap()
    {
        $search = [
            [  1,  0 ], // east
            [ -1,  0 ], // west
            [  0,  1 ], // down (1000)
            [  0, -1 ], // up
        ];

        $changes = 1;

        $iterations = 0;
        while ($changes) {
            $changes = 0;
            foreach ($this->map as $y => $row) {
                foreach ($row as $x => $val) {
                    if ($val == '#') {
                        continue;
                    }

                    if ($val <= 1) {
                        continue;
                    }

                    $lowestNeighbor = 9999999999;
                    foreach ($search as [ $xS, $yS ]) {
                        if (!isset($this->map[$y + $yS][$x + $xS]) ||
                            $this->map[$y + $yS][$x + $xS] == '#'
                        ) {
                            continue;
                        }

                        if ($this->map[$y + $yS][$x + $xS] < $lowestNeighbor) {
                            $lowestNeighbor = $this->map[$y + $yS][$x + $xS];
                        }
                    }

                    if ($this->map[$y][$x] > $lowestNeighbor + 1) {
                        $this->map[$y][$x] = $lowestNeighbor + 1;
                        $changes++;
                    }
                }
            }

            $iterations++;

            // just in case
            if ($iterations >= 500) {
                echo "Went over max iterations\n";
                break;
            }
        }
    }

    protected function drawMap()
    {
        foreach ($this->map as $y => $row) {
            foreach ($row as $x => $tile) {
                if ($tile == '#') {
                    echo $tile;
                } else {
                    echo $tile;
                    //echo substr("{$tile}", -1);
                }
            }
            echo "\n";
        }
    }
}
