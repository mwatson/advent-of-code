<?php

class Day01 extends Day
{
    public function part1()
    {
        $lines = explode("\n", $this->data);

        $point = 50;
        $count = 0;

        foreach ($lines as $line) {
            $dir = $line[0];
            $num = (int) substr($line, 1);

            if ($dir == "R") {
                $point += $num;
            } else if ($dir == "L") {
                $point -= $num;
            }

            while ($point > 99) {
                $point -= 100;
            }

            while ($point < 0) {
                $point += 100;
            }

            if ($point == 0) {
                $count++;
            }
        }

        return $count;
    }

    public function part2()
    {
        $lines = explode("\n", $this->data);

        $point = 50;
        $count = 0;

        foreach ($lines as $line) {
            $dir = $line[0];
            $num = (int) substr($line, 1);

            if ($dir == "R") {
                $point += $num;
            } else if ($dir == "L") {
                $point -= $num;
            }

            while ($point > 99) {
                $point -= 100;
                $count++;
            }

            while ($point < 0) {
                $point += 100;
                $count++;
            }
        }

        return $count;
    }
}
