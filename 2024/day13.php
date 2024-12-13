<?php

class Day13 extends Day
{
    protected $pos = 0;

    public function part1()
    {
        $this->getNextMachine();
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

        //print_r($info);

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
        }

        return $machine;
    }
}