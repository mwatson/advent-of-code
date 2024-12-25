<?php

class Day24 extends Day
{
    protected $gates = [];

    protected $queue = [];

    public function part1()
    {
        $this->init();

        $this->runQueue();

        ksort($this->gates);

        $result = 0;
        $digit = 0;
        foreach ($this->gates as $gate => $val) {
            if (strpos($gate, 'z') === 0) {
                $result += $val << $digit;
                $digit++;
            }
        }

        return $result;
    }

    public function part2()
    {
        $this->init();

        $this->runQueue();

        ksort($this->gates);

        $result = [
            'x' => 0,
            'y' => 0,
            'z' => 0,
        ];
        $digit = [
            'x' => 0,
            'y' => 0,
            'z' => 0,
        ];

        foreach ($this->gates as $gate => $val) {

            foreach ([ 'x', 'y', 'z' ] as $d) {
                if (strpos($gate, $d) === 0) {
                    $result[$d] += $val << $digit[$d];
                    $digit[$d]++;
                }
            }
        }

        $xVal = array_reverse(array_map('intval', str_split(sprintf("%b", $result['x']))));
        $yVal = array_reverse(array_map('intval', str_split(sprintf("%b", $result['y']))));
        $zVal = array_reverse(array_map('intval', str_split(sprintf("%b", $result['z']))));

        $carry = 0;
        foreach ($xVal as $i => $xDig) {
            $yDig = $yVal[$i];
            $zDig = $zVal[$i];
            
            if ($yDig != $xDig) {
                if ($zDig !== 1) {

                }
            } else {
                // 1 + 1 = 10, 0 + 0 = 00
                if ($zDig !== 0) {
                }
            }
        }

        return 0;
    }

    protected function init()
    {
        $lines = explode("\n", $this->data);

        $mode = 'values';

        foreach ($lines as $line) {
            if (empty($line)) {
                $mode = 'ops';
                continue;
            }

            if ($mode == 'values') {
                [ $gate, $val ] = explode(': ', $line);

                $this->gates[$gate] = (int) $val;
            } else if ($mode == 'ops') {
                [ $gate1, $op, $gate2, $arrow, $gate3 ] = explode(' ', $line);

                $method = "op{$op}";

                $this->queue[] = [ $method, $gate1, $gate2, $gate3 ];
            }
        }
    }

    protected function runQueue()
    {
        while (count($this->queue)) {

            [ $method, $gate1, $gate2, $gate3 ] = array_shift($this->queue);

            if (!isset($this->gates[$gate1]) || 
                !isset($this->gates[$gate2])
            ) {
                $this->queue[] = [ $method, $gate1, $gate2, $gate3 ];
                continue;
            }

            $this->gates[$gate3] = $this->$method($this->gates[$gate1], $this->gates[$gate2]);
        }
    }

    protected function opAND($a, $b)
    {
        return $a & $b;
    }

    protected function opOR($a, $b)
    {
        return $a | $b;
    }

    protected function opXOR($a, $b)
    {
        return $a ^ $b;
    }
}
