<?php

// lol
ini_set('memory_limit', '1000M');

class Day11 extends Day
{
	protected $head = null;

    protected $stones = [];

    public function part1()
    {
    	$this->setup();

        for ($i = 0; $i < 25; $i++) {
            $this->blink();
        }

        return count($this->stones);
    }

    public function part2()
    {
    	// this wasn't fast enough, but leaving it
    	// DOWNLOAD MORE RAM
    	// instead of running blink X times on all stones we 
    	// can save memory (hopefully) by running the rules on
    	// each individual stone X times... this requires keeping
    	// track of new stones and how many times the process should
    	// be run on them. for the sake of argument say we start with
    	// just one stone, labled 0, and we blink 6 times:
    	// 0 -> 1 -> 2024 -> 20, 24 -> 2, 0, 2, 4 -> 4048, 1, 4048, 8096 -> 40, 48, 2024, 40, 48, 80, 96 -> etc
    	// the original stone would be:   [ 0, 1, 2024, 20, 2, 4048, 40 ]
    	// and the second stone would be: [ -, -, -,    24, 0, 1,    48 ]
    	// third stone:                   [ -, -, -,     -, 2, 4048, 40 ]
    	// etc etc
    	// since the surrounding stones have no impact on what happens to
    	// an individual stone, we can run blinks on our stone, and every time
    	// we create a second stone add it to the queue with the number of times
    	// we need to run blink on it (which is whatever number of blinks our
    	// current stone has left).

    	// --- 

    	// important note is that sequences are deterministic, so once you have a sequence
    	// everything that comes after it will always be the same


    	$this->data = "0";

    	$this->echoOn();
    	
    	$stones = array_map(function($number) {
    		return (object) [
    			'v' => (int) $number,
    			'x' => 75,
    		];
    	}, explode(" ", $this->data));

    	$stoneCount = count($stones);

    	$sequence = [];

    	do {
    		$this->echo(count($stones) . " stones to go\n");

    		// it doesn't matter where we grab stones from, pop is probably more performant
    		$stone = array_pop($stones);

    		$this->echo("Processing stone {$stone->v} [{$stone->x} binks] ");

    		$sequence[] = $stone->v;

    		while ($stone->x > 0) {
    			$str = "{$stone->v}";
    			$len = strlen($str);

    			if ($stone->v == 0) {
    				$stone->v = 1;
    			} else if (strlen($str) % 2 == 0) {
    				$stone->v = (int) substr($str, 0, $len / 2);

    				if ($stone->x > 1) {
	    				$stones[] = (object) [
	    					'v' => (int) substr($str, $len / 2),
	    					'x' => $stone->x - 1,
	    				];

	    				$this->echo("-> created new stone w " . ($stone->x - 1) . " blinks ");
	    			}

    				$stoneCount++;

    			} else {
    				$stone->v *= 2024;
    			}

    			$sequence[] = $stone->v;

    			$this->echo("-> {$stone->v} ");

    			$stone->x--;
    		}

    		print_r($sequence);
    		die;

    		$this->echo("DONE\n");

    	} while (count($stones) > 0);

    	return $stoneCount;
    }

    // this became more complicated because I assumed it would solve
    // our memory issues. for 25 stones you can just use a simple array
    // and recreate it every iteration of blink()
    protected function setup()
    {
		$this->stones = array_map(function($number) {
			return (object) [
				'v' => intval($number),
				'n' => null,
			];
		}, explode(" ", $this->data));

		$this->head = &$this->stones[0];

		foreach ($this->stones as $i => $stone) {
			if (!empty($this->stones[$i + 1])) {
				$this->stones[$i]->n = &$this->stones[$i + 1];
			}
		}
    }

    public function blink()
    {
        $cur = $this->head;

        while ($cur != null) {
            if ($cur->v == 0) {
                $cur->v = 1;

            //} else if ($this->numDigits($stone) % 2 == 0) {
            } else if (strlen("{$cur->v}") % 2 == 0) { // benchmark: ~90ms for 25 (faster w/out function call)
                $s = "{$cur->v}";
                $l = strlen($s);

                $cur->v = (int) substr($s, 0, $l / 2);

                $this->stones[] = (object) [ 
                	'v' => (int) substr($s, $l / 2),
                	'n' => $cur->n,
                ];

                $cur->n = &$this->stones[count($this->stones) - 1];
                $cur = $cur->n;

            } else {
            	$cur->v *= 2024;
            }

            $cur = $cur->n;
        }
    }

    // testing a few methods, and surprisingly string conversion is fastest
    protected function numDigits($number) {
    	// benchmark: ~160ms for 25
    	return strlen(sprintf("%d", $number));

    	// benchmark: ~126ms for 25
    	return strlen("{$number}");

    	// benchmark: ~186ms for 25
    	return $number !== 0 ? floor(log10($number) + 1) : 1;


    	// benchmark: ~237ms for 25
    	$count = 0;
    	while ($number > 0) {
    		$number = floor($number / 10);
    		$count++;
    	}
    	return $count;
    }
}
