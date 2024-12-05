<?php

class Day05 extends Day
{
	protected $invalidJobs = [];
	protected $combined = [];

	public function part1()
	{
		$lines = explode("\n", $this->data);

		$rules = [];
		$jobs = [];

		$process = "rules";
		foreach ($lines as $line) {
		    if (!strlen($line)) {
		        $process = "jobs";
		        continue;
		    }

		    if ($process == "rules") {
		        $rules[] = $line;
		    } else if ($process == "jobs") {
		        $jobs[] = explode(',', $line);
		    }
		}

		sort($rules);

		foreach ($rules as $rule) {
		    [ $x, $y ] = explode('|', $rule);
		    if (empty($combined[$x])) {
		        $this->combined[$x] = [];
		    }

		    $this->combined[$x][$y] = true;
		}

		$total = 0;

		foreach ($jobs as $job) {
			$validJob = true;
		    foreach ($job as $i => $pageNum) {
		    	// $pageNum is the individual page
		    	// we need to make sure all the pages BEFORE
		    	// it DON'T appear in $pageNum's list:
		    	$list = $this->combined[$pageNum];

		    	// look at everything before $job[$i] (this might be nothing)
		    	for ($x = 0; $x < $i; $x++) {
		    		$page = $job[$x];
		    		// if this page is on THE LIST
		    		if (!empty($list[$page])) {
		    			// then it's not a valid job
		    			$validJob = false;
		    			break;
		    		}
		    	}

		    	// once a job has been flagged invalid we don't need to check anything else
		    	if (!$validJob) {
		    		break;
		    	}
		    }

		    if ($validJob) {
		    	$total += $job[floor(count($job) / 2)];
		    } else {
		    	$this->invalidJobs[] = $job;
		    }
		}

		return $total;
	}

	public function part2()
	{
		$this->echoOn();

		// this could be done as part of the above loop,
		// but doing it here just to make things easier

		// 41 42 99 65 12 should be

		//$this->invalidJobs = [ [ 41, 42, 99, 65, 12 ] ];

		$total = 0;

		foreach ($this->invalidJobs as &$job) {
			$moves = [];
			foreach ($job as $i => $pageNum) {
		    	$list = $this->combined[$pageNum];

		    	// look at everything before $job[$i] (this might be nothing)
		    	for ($x = 0; $x < $i; $x++) {
		    		$page = $job[$x];
		    		// if this page is on THE LIST
		    		if (!empty($list[$page])) {
		    			// we need to move it here (before it)
		    			$moves[$i] = $x;
		    		}
		    	}
		    }

		    print_r($moves);

		    $this->echo("[ " . implode(' ', $job) . " ] ->\n");

		    /*
		    $newJob = [];
			foreach ($job as $i => $pageNum) {
				if (empty($moves[$i])) {
					$newJob[] = $pageNum;
				} else {
					$newJob = array_merge(
						array_slice($newJob, 0, $moves[$i] - 1),
						[ $pageNum ],
						array_slice($newJob, $moves[$i])
					);
				}
			}
			*/

			//$job = $newJob;

		    foreach ($moves as $x => $before) {
		    	$moveThis = $job[$x];
		    	
		    	//

		    	unset($job[$x]);
		    	$job = array_values($job);

		    	$job = array_merge(
		    		array_slice($job, 0, $before ),
		    		[ $moveThis ],
		    		array_slice($job, $before )
		    	);

		    	//array_splice($job, $x + 1, 1);
		    }

		    $this->echo("[ " . implode(' ', $job) . " ]\n============\n");

		    $total += $job[floor(count($job) / 2)];

		    return $total;
		}
	}
}

