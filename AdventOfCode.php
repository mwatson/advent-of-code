<?php

class AdventOfCode
{
	protected $day;

	protected $results = [];
	protected $timing = [];

    public function __construct(int $year, int $day)
    {
    	$yearDir = __DIR__ . "/{$year}";
    	if (!file_exists($yearDir)) {
    		throw new Exception("Could not find year {$year}");
    	}

    	if ($day < 10) {
    		$day = "0{$day}";
    	}

    	$dayFile = "{$yearDir}/day{$day}.php";
    	if (!file_exists($dayFile)) {
    		throw new Exception("Could not find PHP file for {$year}/{$day}");
    	}

    	// we don't need no stinkin autoloader
    	include($dayFile);

    	$dayClass = "Day{$day}";

    	$inputFile = __DIR__ . "/{$year}/puzzle/day{$day}.txt";
    	if (!file_exists($inputFile)) {
    		throw new Exception("Could not find text file for {$year}/{$day}");
    	}

    	$this->day = new $dayClass($inputFile);
    }

    public function exec()
    {
    	$st = microtime(true);
    	$this->results[] = $this->day->run(1);
    	$en = microtime(true);
    	$this->timing[] = round(($en - $st) * 1000, 4);

    	$st = microtime(true);
    	$this->results[] = $this->day->run(2);
    	$en = microtime(true);
    	$this->timing[] = round(($en - $st) * 1000, 4);
    }

    public function displayResults()
    {
    	echo "Part 1: {$this->results[0]}\n";
    	echo " (Runtime: {$this->timing[0]}ms]\n";
    	echo "Part 2: {$this->results[1]}\n";
    	echo " [Runtime: {$this->timing[1]}ms]\n";
    }
}
