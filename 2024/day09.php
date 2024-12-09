<?php

class Day09 extends Day
{
	protected $fs = [];

    public function part1()
    {
    	$fileId = 0;

    	$this->fs = [];

    	for ($i = 0; $i < strlen($this->data); $i++) {
    		$val = (int) $this->data[$i];
    		$fill = $i % 2 ? false : $fileId;

    		for ($x = 0; $x < $val; $x++) {
    			$this->fs[] = $fill;
    		}

    		if (!($i % 2)) {
    			$fileId++;
    		}
    	}

    	$emptyIndex = $this->findEmpty(0);

    	for ($i = count($this->fs) - 1; $i >= 0; $i--) {
    		if ($this->fs[$i] === false) {
    			continue;
    		}

    		$this->fs[$emptyIndex] = $this->fs[$i];
    		$this->fs[$i] = false;

    		$emptyIndex = $this->findEmpty($emptyIndex + 1);

    		if ($emptyIndex >= $i) {
    			break;
    		}
    	}

    	return $this->calcChecksum();
    }

    public function part2()
    {
    	$this->echoOn();

    	$this->data = "2333133121414131402";

    	$fileId = 0;

    	$this->fs = [];

    	for ($i = 0; $i < strlen($this->data); $i++) {
    		$val = (int) $this->data[$i];
    		$fill = $i % 2 ? false : $fileId;

    		

    		if (!($i % 2)) {
    			$fileId++;
    		}
    	}

    	return 0;
    }

    protected function calcChecksum()
    {
    	$checkSum = 0;
    	for ($i = 0; $i < count($this->fs); $i++) {
    		if ($this->fs[$i] === false) {
    			continue;
    		}
    		$checkSum += $this->fs[$i] * $i;
    	}

    	return $checkSum;
    }

    // this used to work for part 2 but I changed the implementatio
    protected function findEmpty($start, $minLength = 1)
    {
    	for ($i = $start; $i < count($this->fs); $i++) {
    		if ($this->fs[$i] === false) {
    			$blocksFound = 0;
    			for ($j = $i; $i < $i + $minLength; $j++) {
    				if (!array_key_exists($j, $this->fs)) {
    					break;
    				}

    				if ($this->fs[$j] === false) {
    					$blocksFound++;
    				} else {
    					break;
    				}
    			}

    			if ($blocksFound >= $minLength) {
    				return $i;
    			}
    		}
    	}

    	return false;
    }
}
