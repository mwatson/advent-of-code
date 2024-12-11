<?php

// lol
ini_set('memory_limit', '2000M');

class Day11 extends Day
{
    protected $stones = [];

    public function part1()
    {
        $this->stones = array_map('intval', explode(" ", $this->data));

        for ($i = 0; $i < 25; $i++) {
            $this->stones = $this->blink();
        }

        return count($this->stones);
    }

    public function part2()
    {
        $this->stones = array_map('intval', explode(" ", $this->data));

        for ($i = 0; $i < 75; $i++) {
            $this->stones = $this->blink();
        }

        return count($this->stones);
    }

    public function blink()
    {
        $newStones = [];

        foreach ($this->stones as $stone) {
            if ($stone == 0) {
                $newStones[] = 1;

            // this seems like a bad way to do this
            } else if (strlen("{$stone}") % 2 == 0) {
                $s = "{$stone}";
                $l = strlen($s);

                $newStones[] = (int) substr($s, 0, $l / 2);
                $newStones[] = (int) substr($s, $l / 2);

            } else {
                $newStones[] = $stone * 2024;
            }
        }

        echo count($newStones) . "\n";

        return $newStones;
    }
}
