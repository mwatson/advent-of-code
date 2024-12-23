<?php

class Day21 extends Day
{
    public function part1()
    {
        $this->data = "029A";

        // w/map (closest move but not always shortest path):
        // <A^A^^>AvvvA (12)
        // v<<A>^>A<A>A<AAv>A^Av<AAA^>A (28)
        // v<A<AA>^>AvA^<Av>A^Av<<A>^>AvA^A<v<A>^>AAv<A>A^A<A>Av<A<A>>^AAA<A>vA^A (70)

        // v<<A>^>A<A>AvA^<AA>Av<AAA^>A
        // v<A<AA>>^AvA^<A>vA^Av<<A>>^AvA^Av<A^>A<Av<A>^>AAvA^Av<A<A>^>AAA<A>vA^A



        //$this->data = "463A";//\n340A";//\n129A\n083A\n341A";

        $sequences = $this->getSequences();

        $keypad = new Keypad(3, 4, [ 7, 8, 9, 4, 5, 6, 1, 2, 3, null, 0, 'A' ]);
        $keypad->setPosition(2, 3);

        $dirpad1 = new Keypad(3, 2, [ null, 'UP', 'ACTION', 'LEFT', 'DOWN', 'RIGHT' ]);
        $dirpad1->setPosition(2, 0);

        $dirpad2 = new Keypad(3, 2, [ null, 'UP', 'ACTION', 'LEFT', 'DOWN', 'RIGHT' ]);
        $dirpad2->setPosition(2, 0);

        $result = 0;

        $this->echoOn();

        foreach ($sequences as $sequence) {
            foreach ($sequence['presses'] as $i => $press) {
                $keypad->pressButton($press, $i, $sequence['presses']);
            }
            $presses = $keypad->getPressSequence();

            $this->echo($this->pressesToString($presses) . "\n");

            foreach ($presses as $i => $press) {
                $dirpad1->pressButton($press, $i, $presses);
            }
            $presses = $dirpad1->getPressSequence();

            $this->echo($this->pressesToString($presses) . "\n");

            foreach ($presses as $i => $press) {
                $dirpad2->pressButton($press, $i, $presses);
            }
            $presses = $dirpad2->getPressSequence();

            $this->echo($this->pressesToString($presses) . "\n");

            $result += count($presses) * $sequence['val'];
        }

        echo "\n";

        return $result;
    }

    public function part2()
    {
        return 0;
    }

    public function getSequences()
    {
        $sequences = [];

        $lines = explode("\n", $this->data);
        foreach ($lines as $i => $line) {
            $sequences[] = [
                'presses' => [],
                'val' => (int) $line, // bless you, php
            ];
            $buttons = str_split($line);
            foreach ($buttons as $button) {
                if (is_numeric($button)) {
                    $sequences[$i]['presses'][] = (int) $button;
                } else {
                    $sequences[$i]['presses'][] = $button;
                }
            }
        }

        return $sequences;
    }

    public function pressesToString($presses) {
        $str = "";
        foreach ($presses as $press) {
            switch ($press) {
                case "UP":
                    $str .= "^";
                    break;
                case "DOWN":
                    $str .= "v";
                    break;
                case "LEFT":
                    $str .= "<";
                    break;
                case "RIGHT":
                    $str .= ">";
                    break;
                case "ACTION":
                    $str .= "A";
                    break;
            }
        }

        return $str;
    }
}

class Keypad
{
    protected $buttons;

    protected $position = [];

    protected $path = [];

    public function __construct($width, $height, $buttons)
    {
        $this->buttons = [];

        $ch = array_chunk($buttons, $width);

        foreach ($ch as $row) {
            $btnRow = [];
            foreach ($row as $btn) {
                $btnRow[] = [
                    'button' => $btn,
                    'score' => 99999999,
                ];
            }
            $this->buttons[] = $btnRow;
        }

        //print_r($this->buttons);
    }

    public function setPosition($x, $y)
    {
        $this->position = [ $x, $y ];
    }

    public function findPath()
    {
        $dist = [];
        $prev = [];
        $queue = [];
        foreach ($this->buttons as $y => $row) {
            foreach ($row as $x => $_) {
                $k = "{$x}-{$y}";
                $dist[$k] = 999999999;
                $prev[$k] = null;
                $queue[$k] = [ $x, $y ];
            }
        }

        [ $x, $y ] = $this->position;
        $dist["{$x}-{$y}"] = 0;

        $search = [
            [  0, -1 ],
            [  1,  0 ],
            [  0,  1 ],
            [ -1,  0 ],
        ];

        while (count($queue)) {
            $shortDist = -1;
            $shortItem = "";
            $shortCoords = [];
            foreach ($queue as $item => $coords) {
                if ($shortDist == -1 || $dist[$item] < $shortDist) {
                    $shortDist = $dist[$item];
                    $shortItem = $item;
                    $shortCoords = $coords;
                }
            }

            unset($queue[$shortItem]);

            [ $shortX, $shortY ] = $shortCoords;

            foreach ($search as [ $sX, $sY ]) {
                $xCheck = $shortX + $sX;
                $yCheck = $shortY + $sY;
                $chKey = "{$xCheck}-{$yCheck}";

                if (!isset($queue[$chKey]) ||
                    empty($this->buttons[$yCheck][$xCheck]) ||
                    $this->buttons[$yCheck][$xCheck]['button'] === null
                ) {
                    continue;
                }

                // is this right???
                $alt = $dist[$shortItem] + $dist[$chKey];

                if ($alt < $dist[$chKey]) {
                    $dist[$chKey] = $alt;
                    $prev[$chKey] = $shortItem;
                }
            }
        }

        print_r($dist);
    }

    public function pressButton($key, $curButton, $sequence)
    {
        $search = [
            [  0, -1 ],
            [  0,  1 ],
            [ -1,  0 ],
            [  1,  0 ],
        ];

        $this->scoreMap($key);

        while (1) {
            [ $x, $y ] = $this->position;

            if ($this->buttons[$y][$x]['score'] === 0) {
                $this->path[] = 'ACTION';
                break;
            }

            $valid = [];

            foreach ($search as [ $xS, $yS ]) {
                $xC = $x + $xS;
                $yC = $y + $yS;

                if (empty($this->buttons[$yC][$xC])) {
                    continue;
                }
                if ($this->buttons[$yC][$xC]['button'] === null) {
                    continue;
                }

                if ($this->buttons[$yC][$xC]['score'] == $this->buttons[$y][$x]['score'] - 1) {
                    $valid[] = [ $xC, $yC ];
                }
            }

            $r = 0;

            // if we find more than one path, try to find the one
            // that has a smaller distance to the next press
            if (count($valid) > 1 && isset($sequence[$curButton + 1])) {
                $next = $sequence[$curButton + 1];
                [ $nextX, $nextY ] = $this->findButtonCoords($next);
                $best = -1;
                $bestDist = 999;
                foreach ($valid as $v => [ $vX, $vY ]) {
                    $dist = abs($vX - $nextX) + abs($vY - $nextY);
                    echo "{$v} {$dist}\n";
                    if ($dist < $bestDist) {
                        $best = $v;
                        $bestDist = $dist;
                    }
                }

                $r = $best;

                echo "{$r}\n";

                echo "--\n";
            }

            [ $xC, $yC ] = $valid[$r];

            if ($xC > $x) {
                $this->path[] = 'RIGHT';
            } else if ($xC < $x) {
                $this->path[] = 'LEFT';
            } else if ($yC > $y) {
                $this->path[] = 'DOWN';
            } else if ($yC < $y) {
                $this->path[] = 'UP';
            }
            $this->setPosition($xC, $yC);
        }

        return $this;
    }

    public function scoreMap($goal)
    {
        foreach ($this->buttons as $y => $row) {
            foreach ($row as $x => $button) {
                if ($button['button'] === $goal) {
                    $this->buttons[$y][$x]['score'] = 0;
                } else if ($button['button'] !== null) {
                    $this->buttons[$y][$x]['score'] = 99999999;
                }
            }
        }

        $search = [
            [  0, -1 ],
            [  0,  1 ],
            [ -1,  0 ],
            [  1,  0 ],
        ];

        $changes = 1;

        while ($changes) {
            $changes = 0;

            foreach ($this->buttons as $y => $row) {
                foreach ($row as $x => $button) {

                    if ($this->buttons[$y][$x]['button'] === null) {
                        continue;
                    }

                    $lowest = 99999999;
                    foreach ($search as [ $xS, $yS ]) {
                        $xC = $x + $xS;
                        $yC = $y + $yS;

                        if (empty($this->buttons[$yC][$xC])) {
                            continue;
                        }
                        if ($this->buttons[$yC][$xC]['button'] === null) {
                            continue;
                        }

                        if ($this->buttons[$yC][$xC]['score'] < $lowest) {
                            $lowest = $this->buttons[$yC][$xC]['score'];
                        }
                    }

                    if ($this->buttons[$y][$x]['score'] > $lowest + 1) {
                        $this->buttons[$y][$x]['score'] = $lowest + 1;
                        $changes++;
                    }
                }
            }
        }
    }

    public function getPressSequence()
    {
        return $this->path;
    }

    protected function findButtonCoords($key)
    {
        foreach ($this->buttons as $y => $row) {
            foreach ($row as $x => $btn) {
                if ($btn['button'] === $key) {
                    return [ $x, $y ];
                }
            }
        }

        throw new Exception("Button {$key} not found");
    }
}
