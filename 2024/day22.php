<?php

class Day22 extends Day
{
    public function part1()
    {
        $numbers = $this->getNumbers();

        $total = 0;
        foreach ($numbers as $number) {
            $num = $number;
            for ($i = 0; $i < 2000; $i++) {
                $num = $this->getNext($num);
            }
            $total += $num;
        }

        return $total;
    }

    public function part2()
    {
        return 0;
    }

    protected function getNext($num)
    {
        $m = $num * 64;
        $m = $m ^ $num;
        $m %= 16777216;

        $n = floor($m / 32);
        $n = $n ^ $m;
        $n %= 16777216;

        $o = $n * 2048;
        $o = $o ^ $n;
        $o %= 16777216;

        return $o;
    }

    protected function getNumbers()
    {
        return array_map('intval', explode("\n", $this->data));
    }
}
