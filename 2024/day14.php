<?php

class Day14 extends Day
{
    protected $mapW = 101;
    protected $mapH = 103;

    public function part1()
    {
        /*
        $this->data = "p=0,4 v=3,-3
p=6,3 v=-1,-3
p=10,3 v=-1,2
p=2,0 v=2,-1
p=0,0 v=1,3
p=3,0 v=-2,-2
p=7,6 v=-1,-3
p=3,0 v=-1,-2
p=9,3 v=2,3
p=7,3 v=-1,2
p=2,4 v=2,-3
p=9,5 v=-3,-3";

        $this->mapW = 11;
        $this->mapH = 7;
        */

        $robots = $this->getInitRobots();


        /*
        for ($i = 0; $i < 100; $i++) {
            $this->advanceRobots($robots);
        }
        */

        $this->advanceRobots($robots, 100);

        //$this->drawRobots($robots);

        $quads = [
            0 => 0, // x = 0 - 49
            1 => 0, // x = 51 - 100
            2 => 0, // x = 0 - 49
            3 => 0, // x = 51 - 100
        ];

        foreach ($robots as $robot) {
            if ($robot['x'] < floor($this->mapW / 2)) {
                if ($robot['y'] < floor($this->mapH / 2)) {
                    $quads[0]++;
                } else if ($robot['y'] > floor($this->mapH / 2)) {
                    $quads[1]++;
                }
            } else if ($robot['x'] > floor($this->mapW / 2)) {
                if ($robot['y'] < floor($this->mapH / 2)) {
                    $quads[2]++;
                } else if ($robot['y'] > floor($this->mapH / 2)) {
                    $quads[3]++;
                }
            }
        }

        return $quads[0] * $quads[1] * $quads[2] * $quads[3];;
    }

    public function part2()
    {
        $robots = $this->getInitRobots();

        $neighbors = [];
        for ($i = 1; $i <= 10500; $i++) {
            $this->advanceRobots($robots, 1);
            $n = $this->getAllNeighbors($robots);
            //echo "{$i}: {$n} neighbors\n";
            $neighbors[$i] = [ $i, $n ];
        }
        
        usort($neighbors, function($a, $b) {
            return $a[1] > $b[1] ? -1 : 1;
        });

        $outlier = $neighbors[0][0];

        $robots = $this->getInitRobots();
        $this->advanceRobots($robots, $outlier);
        //$this->advanceRobots($robots, 4578);
        
        $this->drawRobots($robots);

        return $outlier;
    }

    protected function getInitRobots()
    {
        $lines = explode("\n", $this->data);

        $robots = [];

        foreach ($lines as $line) {

            $matches = [];
            if (!preg_match('/^p\=([-0-9]+),([-0-9]+) v\=([-0-9]+),([-0-9]+)$/', $line, $matches)) {
                continue;
            }

            $robots[] = [
                'x' => (int) $matches[1],
                'y' => (int) $matches[2],
                'vX' => (int) $matches[3],
                'vY' => (int) $matches[4],
            ];
        }

        return $robots;
    }

    // for now this juist advances 1s
    protected function advanceRobots(&$robots, $seconds = 1)
    {
        foreach ($robots as $i => $robot) {
            $robot['x'] += ($robot['vX'] * $seconds) % $this->mapW;

            if ($robot['x'] >= $this->mapW) {
                $robot['x'] = $robot['x'] - $this->mapW;
            } else if ($robot['x'] < 0) {
                $robot['x'] = $this->mapW + $robot['x'];
            }

            $robot['y'] += ($robot['vY'] * $seconds) % $this->mapH;

            if ($robot['y'] >= $this->mapH) {
                $robot['y'] = $robot['y'] - $this->mapH;
            } else if ($robot['y'] < 0) {
                $robot['y'] = $this->mapH + $robot['y'];
            }

            $robots[$i]['x'] = $robot['x'];
            $robots[$i]['y'] = $robot['y'];
        }
    }

    protected function drawRobots($robots, $adv = 1) {
        $robotPos = [];
        foreach ($robots as $robot) {
            $k = "{$robot['x']}-{$robot['y']}";
            if (!isset($robotPos[$k])) {
                $robotPos[$k] = 0;
            }
            $robotPos[$k]++;
        }

        for ($y = 0; $y < $this->mapH; $y += $adv) {
            for ($x = 0; $x < $this->mapW; $x += $adv) {
                if (!empty($robotPos["{$x}-{$y}"])) {
                    echo $robotPos["{$x}-{$y}"];
                } else {
                    echo ".";
                }
            }
            echo "\n";
        }
    }

    protected function getAllNeighbors($robots)
    {
        $robotCoords = [];

        foreach ($robots as $robot) {
            [ 'x' => $x, 'y' => $y ] = $robot;

            if (empty($robotCoords[$y])) {
                $robotCoords[$y] = [];
            }
            $robotCoords[$y][$x] = true;
        }

        $search = [
            [  0, -1 ],
            [  1,  0 ],
            [  0,  1 ],
            [ -1,  0 ],
        ];

        $neighbors = 0;
        foreach ($robots as $robot) {
            [ 'x' => $x, 'y' => $y ] = $robot;
            foreach ($search as [ $sX, $sY ]) {
                if (!empty($robotCoords[$y + $sY][$x + $sX])) {
                    $neighbors++;
                }
            }
        }

        return $neighbors;
    }
}
