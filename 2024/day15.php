<?php

class Day15 extends Day
{
    protected $map = [];

    protected $instructions = [];

    protected $player = [
        'x' => 0,
        'y' => 0,
    ];

    public function part1()
    {
        $this->parseData();

        //$this->drawMap();

        foreach ($this->instructions as $i => $instruction) {
            $this->runInstruction($instruction);
            //echo "RUN ({$instruction[0]}, {$instruction[1]})\n";
            //$this->drawMap();
        }

        $this->drawMap();

        return $this->calcGPS();
    }

    public function part2()
    {
        return 0;
    }

    protected function runInstruction($move)
    {
        [ $x, $y ] = $move;
        [ 'x' => $pX, 'y' => $pY ] = $this->player;

        $newX = $this->player['x'] + $x;
        $newY = $this->player['y'] + $y;

        $occupant = $this->map[$newY][$newX];

        // move into an empty space
        if ($occupant == '.') {
            $this->map[$pY][$pX] = '.';
            $this->map[$newY][$newX] = '@';

            $this->player['x'] = $newX;
            $this->player['y'] = $newY;
            return;
        }

        // move into a wall (can't do it)
        if ($occupant == '#') {
            return;
        }

        // move into a box (push!)
        if ($occupant == 'O') {
            if ($this->pushBoxIfPossible($newX, $newY, $x, $y)) {
                $this->map[$pY][$pX] = '.';
                $this->map[$newY][$newX] = '@';

                $this->player['x'] = $newX;
                $this->player['y'] = $newY;
            }
        }
    }

    protected function pushBoxIfPossible($x, $y, $xDir, $yDir)
    {
        if ($this->map[$y][$x] != 'O') {
            throw new Exception("not a box");
        }

        $checkSpot = $this->map[$y + $yDir][$x + $xDir];
        if ($checkSpot == '.') {
            $this->map[$y + $yDir][$x + $xDir] = 'O';
            $this->map[$y][$x] = '.';
            return true;
        }
        if ($checkSpot == '#') {
            return false;
        }
        if ($checkSpot == 'O') {
            if ($this->pushBoxIfPossible($x + $xDir, $y + $yDir, $xDir, $yDir)) {
                $this->map[$y + $yDir][$x + $xDir] = 'O';
                $this->map[$y][$x] = '.';
                return true;
            }
        }
    }

    protected function calcGPS()
    {
        $score = 0;
        foreach ($this->map as $y => $row) {
            foreach ($row as $x => $tile) {
                if ($tile == 'O') {
                    $score += ($y * 100) + $x;
                }
            }
        }

        return $score;
    }

    protected function parseData()
    {
        $lines = explode("\n", $this->data);

        $instructionMap = [
            '^' => [  0, -1 ],
            '>' => [  1,  0 ],
            'v' => [  0,  1 ],
            '<' => [ -1,  0 ],
        ];

        $mode = "map";
        foreach ($lines as $line) {
            if (empty($line)) {
                $mode = "move";
                continue;
            }

            if ($mode == "map") {
                $rowData = str_split($line);
                if (strpos($line, '@') !== false) {
                    $this->player['x'] = strpos($line, '@');
                    $this->player['y'] = count($this->map);
                }
                $row = [];
                foreach ($rowData as $rowItem) {
                    $row[] = $rowItem;
                }
                $this->map[] = $row;

            } else if ($mode == "move") {
                $rowData = str_split($line);
                foreach ($rowData as $dir) {
                    $this->instructions[] = $instructionMap[$dir];
                }
            }
        }
    }

    public function drawMap()
    {
        foreach ($this->map as $y => $row) {
            foreach ($row as $x => $v) {
                echo "{$v}";
            }
            echo "\n";
        }
    }
}
