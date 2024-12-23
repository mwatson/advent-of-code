<?php

class Day23 extends Day
{
	public function part1()
	{
		$triples = $this->getTriples($this->getNodes());

		$validTriples = 0;
		foreach ($triples as [ $a, $b, $c ]) {
			if (strpos($a, 't') === 0 || 
				strpos($b, 't') === 0 ||
				strpos($c, 't') === 0
			) {
				$validTriples++;
			}
		}

		return $validTriples;
	}

	public function part2()
	{
		$sets = $this->getConnectedSets($this->getNodes());

		$longestSet = null;
		$longestAmt = 0;
		foreach ($sets as $set) {
			if (count($set) > $longestAmt) {
				$longestSet = $set;
				$longestAmt = count($set);
			}
		}

		return implode(',', $longestSet);
	}

	protected function getNodes()
	{
		$lines = explode("\n", $this->data);

		$nodes = [];

		foreach ($lines as $line) {
			[ $l, $r ] = explode("-", $line);

			if (!isset($nodes[$l])) {
				$nodes[$l] = [];
			}
			if (!isset($nodes[$r])) {
				$nodes[$r] = [];
			}

			$nodes[$l][$r] = $r;
			$nodes[$r][$l] = $l;
		}

		return $nodes;
	}

	protected function getTriples($nodes)
	{
		$triples = [];

		foreach ($nodes as $base => $connections) {
			foreach ($connections as $node) {
				foreach ($nodes[$node] as $child) {
					if (isset($connections[$child])) {
						$t = [ $base, $node, $child ];
						sort($t);
						$triples["{$t[0]}-{$t[1]}-{$t[2]}"] = [ $base, $node, $child ];
					}
				}
			}
		}

		return array_values($triples);
	}

	protected function getConnectedSets($nodes)
	{
		$sets = [];

		foreach ($nodes as $base => $connections) {
			foreach ($connections as $node) {
				$shared = array_flip(array_values([ $base, $node, ...array_intersect_key($connections, $nodes[$node]) ]));
				
				foreach ($shared as $share => $v) {
					foreach ($shared as $ch => $w) {
						if ($ch == $share) {
							continue;
						}

						if (!isset($nodes[$share][$ch]) || !isset($nodes[$ch][$share])) {
							unset($shared[$share]);
							unset($shared[$ch]);
						}
					}
				}

				ksort($shared);

				$sets[implode('-', array_keys($shared))] = array_keys($shared);
			}
		}

		return array_values($sets);
	}
}