<?php

class Day25 extends Day
{
    protected $locks = [];

    protected $keys = [];

    public function part1()
    {
        $this->data = "#####
.####
.####
.####
.#.#.
.#...
.....

#####
##.##
.#.##
...##
...#.
...#.
.....

.....
#....
#....
#...#
#.#.#
#.###
#####

.....
.....
#.#..
###..
###.#
###.#
#####

.....
.....
.....
#....
#.#..
#.#.#
#####";

        $this->load();

        $fits = 0;

        foreach ($this->locks as $lock) {
            foreach ($this->keys as $key) {
                $success = true;
                foreach ($lock as $i => $pin) {
                    if ($pin + $lock[$i] > 5) {
                        $success = false;
                        break;
                    }
                }

                if ($success) {
                    $fits++;
                }
            }
        }

        return $fits;
    }

    public function part2()
    {
        return 0;
    }

    protected function load()
    {
        $lines = explode("\n", $this->data);

        $current = [];
        foreach ($lines as $line) {
            if (strlen($line) === 0) {
                if ($current[0] == '#####') {
                    $this->locks[] = $this->getHeights($current);
                }
                if ($current[6] == '#####') {
                    $this->keys[] = $this->getHeights($current);
                }

                $current = [];
                continue;
            }

            $current[] = $line;
        }
    }

    protected function getHeights($item)
    {
        $heights = [ 0, 0, 0, 0, 0 ];
        foreach ($item as $row) {
            if ($row == '#####' || $row == '.....') {
                continue;
            }

            for ($i = 0; $i < strlen($row); $i++) {
                if ($row[$i] == '#') {
                    $heights[$i]++;
                }
            }
        }

        return $heights;
    }
}
