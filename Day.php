<?php

abstract class Day
{
	protected $data;

	protected $echoOn = false;

	final public function __construct(string $dataFile)
	{
		$this->data = file_get_contents($dataFile);
	}

	final public function run(int $part)
	{
		if ($part == 1) {
			return $this->part1();
		}

		if ($part == 2) {
			return $this->part2();
		}
	}

	final protected function echoOn()
	{
		$this->echoOn = true;
	}

	final protected function echoOff()
	{
		$this->echoOn = false;
	}

	final protected function echo(string $line)
	{
		if ($this->echoOn) {
			echo $line;
		}
	}

    final protected function echoLine(string $line)
    {
        $this->echo("{$line}\n");
    }

	abstract public function part1();
	abstract public function part2();
}
