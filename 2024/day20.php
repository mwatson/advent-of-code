<?php

class Day20 extends Day
{
    protected $map = [];

    protected $path = [];

    protected $start = [];

    protected $end = [];

    public function part1()
    {
        $this->buildMap();

        $this->traverse($this->start, true);

        //$this->drawMap();

        $count = 0;

        $this->traverse($this->start, false, true, $count);

        return $count;
    }

    public function part2()
    {
    }

    protected function buildMap()
    {
        $lines = explode("\n", $this->data);

        foreach ($lines as $line) {
            $this->map[] = str_split($line);

            if (($s = strpos($line, "S")) !== false) {
                $this->start = [ $s, count($this->map) - 1 ];
            }
            if (($e = strpos($line, "E")) !== false) {
                $this->end = [ $e, count($this->map) - 1 ];
            }
        }
    }

    protected function traverse($from, $setPath = false, $allowCheats = false, &$count = null)
    {
        $traveled = [];

        while (1) {
            $next = $this->navigate($from, $traveled, $setPath, $allowCheats, $count);

            if ($next === true) {
                break;
            }
            if ($next === false) {
                break;
            }

            $from = [ $next[0], $next[1] ];
        }

        return count($traveled);
    }

    protected function navigate($from, &$traveled, $setPath = false, $allowCheats = false, &$count = null)
    {
        $cheated = false;

        $search = [
            [  0, -1 ],
            [  1,  0 ],
            [  0,  1 ],
            [ -1,  0 ],
        ];

        [ $x, $y ] = $from;

        if ($x == $this->end[0] && $y == $this->end[1]) {
            return true;
        }

        $traveled["{$x}-{$y}"] = true;

        foreach ($search as [ $xD, $yD ]) {
            $xC = $x + $xD;
            $yC = $y + $yD;
            $k = "{$xC}-{$yC}";

            if (!empty($traveled[$k])) {
                continue;
            }

            if (!isset($this->map[$yC][$xC])) {
                continue;
            }

            if ($this->map[$yC][$xC] == '#') {
                if ($cheated == false && $allowCheats == true) {
                    // check if this tile has an adjacent empty tile
                    $cheatPossible = false;
                    foreach ($search as [ $xChD, $yChD ]) {
                        $xCheat = $xC + $xChD;
                        $yCheat = $yC + $yChD;

                        // tile doesn't exist
                        if (empty($this->map[$yCheat][$xCheat])) {
                            continue;
                        }

                        // tile is a wall
                        if ($this->map[$yCheat][$xCheat] == '#') {
                            continue;
                        }

                        // tile has been traveled in this path
                        if (!empty($traveled["{$xCheat}-{$yCheat}"])) {
                            continue;
                        }

                        $cheatPossible = true;

                        if (isset($this->path["{$xCheat}-{$yCheat}"]) &&
                            isset($this->path["{$x}-{$y}"])
                        ) {
                            $diff = $this->path["{$x}-{$y}"] - $this->path["{$xCheat}-{$yCheat}"];
                            if ($diff > 100) {
                                $count++;
                            }

                        } else {
                            $cheatPossible = false;
                            continue;
                        }

                        break;
                    }

                    if ($cheatPossible) {
                        $cheated = true;
                    }
                } else {
                    continue;
                }
            }

            //var_dump($setPath);
            if ($setPath) {
                $this->path[$k] = count($traveled) + 1;
            }

            return [ $xC, $yC ];
        }

        return false;
    }

    protected function drawMap()
    {
        foreach ($this->map as $y => $row) {
            foreach ($row as $x => $v) {
                if (!empty($this->path["{$x}-{$y}"])) {
                    echo "\033[32m@\033[0m";
                } else {
                    echo "{$v}";
                }
            }
            echo "\n";
        }
    }
}
