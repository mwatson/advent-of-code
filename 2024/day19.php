<?php

class Day19 extends Day
{
    protected $patterns = [];

    protected $towels = [];

    public function part1()
    {
        $this->init();

        $possible = 0;

        $this->patterns = [
            "gwwgwbgbgbuugwurgggwrubrruuwgbwgwrgwrbwrugwwrrugrwgu",
        ];

        foreach ($this->patterns as $pattern) {
            //echo "{$pattern} : ";
            $res = $this->buildPattern($pattern, [], 0);
            //echo "{$res}\n";

            $possible += $res;
        }

        return $possible;
    }

    public function part2()
    {
        return 0;
    }

    protected function init()
    {
        $lines = explode("\n", $this->data);

        $towels = explode(", ", array_shift($lines));

        sort($towels);

        /*

        g, guu

        g => [
            0 => []
            u => [
                u => [
                    0 => []
                ]
            ]
        ]

        */

        foreach ($towels as $towel) {
            $letters = str_split($towel);

            $ptr = &$this->towels;
            foreach ($letters as $letter) {
                if (empty($ptr[$letter])) {
                    $ptr[$letter] = [];
                }
                $ptr = &$ptr[$letter];
            }

            // signifies the end of a pattern
            $ptr[0] = [];
        }

        array_shift($lines);

        foreach ($lines as $line) {
            $this->patterns[] = $line;
        }
    }

    protected function buildPattern($pattern, $matched, $depth = 0)
    {
        echo " [{$depth}] -> {$pattern}\n";

        $letters = str_split($pattern);
        $ptr = &$this->towels;
        foreach ($letters as $i => $letter) {
            if (isset($ptr[$letter])) {
                $ptr = &$ptr[$letter];
            }
            
            if (isset($ptr[0])) { // end of pattern
                $remainder = substr($pattern, $i + 1);
                if (strlen($remainder) == 0) {
                    return true;
                } else {
                    if (!$this->buildPattern($remainder, $depth + 1)) {
                        continue;
                    }
                }
                break;
            }
        }

        return 0;
    }
}

