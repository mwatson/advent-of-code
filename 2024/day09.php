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
        //$this->data = "2333133121414131402";

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

        $end = count($this->fs) - 1;

        $moved = [];

        while (1) {
            while ($this->fs[$end] === false) {
                $end--;
            }

            [ $start, $len ] = $this->getLastFileInfo($end);
            if ($start == -1) {
                break;
            }

            if (!empty($moved[$this->fs[$start]])) {
                $end = $start - 1;
                continue;
            }

            $emptyStart = $this->findEmpty(0, $len);

            if ($emptyStart > $start) {
                $end = $start - 1;
                continue;
            }

            if ($emptyStart !== false) {
                $fileId = $this->fs[$end];
                for ($i = $emptyStart; $i < $emptyStart + $len; $i++) {
                    $this->fs[$i] = $fileId;
                }
                for ($i = $start; $i < $start + $len; $i++) {
                    $this->fs[$i] = false;
                }

                $moved[$fileId] = true;
            }

            $end = $start - 1;
        }

        return $this->calcChecksum();
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

    protected function getLastFileInfo($max)
    {
        $curId = $this->fs[$max];
        $start = -1;
        for ($i = $max; $i >= 0; $i--) {
            if ($this->fs[$i] != $curId) {
                $start = $i + 1;
                break;
            }
        }

        return [ $start, $max - $start + 1 ];
    }
}
