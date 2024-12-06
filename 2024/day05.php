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

        //$jobs = [ [ 79, 56, 89, 77, 78 ] ]; // invalid
        //$jobs = [ [ 78, 89, 77, 56, 79 ] ]; // valid

		sort($rules);

		foreach ($rules as $rule) {
		    [ $first, $second ] = explode('|', $rule);
		    if (empty($this->combined[$first])) {
		        $this->combined[$first] = [];
		    }

		    $this->combined[$first][$second] = true;
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
                $idx = floor(count($job) / 2);
                $v = $job[$idx];
                //$this->echo('valid: [' . implode(' ', $job) . "] {$idx}=>{$v}\n");
		    	$total += $job[$idx];
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

		// 79, 56, 89, 77, 78 should be
        // 78, 89, 77, 56, 79 (I think?)

        /*
        Progression:

        56 -> 79

        89 -> 56 -> 79
           -> 77

        89 -> 77 -> 56 -> 79

        78 -> 89 -> 77 -> 56 -> 79
           -> 56
           -> 77
           -> 79
           -> 89
        */
    
		$this->invalidJobs = [ [ 79, 56, 89, 77, 78 ] ];

		$total = 0;

		foreach ($this->invalidJobs as $job) {
            $fixedJob = [];

            $pages = [];
            $fJob = array_flip($job);
            foreach ($job as $page) {
                $pageData = [
                    'page' => $page,
                    'after' => [],
                ];

                foreach ($this->combined[$page] as $after => $_) {
                    if (isset($fJob[$after])) {
                        $pageData['after'][] = $after;
                    }
                }

                $pages[] = $pageData;
            }

            print_r($pages);

		    $total += $job[floor(count($job) / 2)];
		}

        return $total;
	}
}

