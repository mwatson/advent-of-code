<?php

class Day25 extends Day
{
    protected $locks = [];

    protected $keys = [];

    public function part1()
    {
        $this->load();

        $fits = [];

        foreach ($this->locks as $lockNum => $lock) {
            foreach ($this->keys as $keyNum => $key) {
                $success = true;
                for ($i = 0; $i < count($key); $i++) {
                    if ($key[$i] + $lock[$i] > 5) {
                        $success = false;
                        break;
                    }
                }

                if ($success) {
                    $fits["l:{$lockNum}-k:{$keyNum}"] = true;
                }
            }
        }

        return count($fits);
    }

    public function part2()
    {
        return 0;
    }

    protected function load()
    {
        $lines = explode("\n", $this->data);
        $lines[] = "";

        $current = [];
        foreach ($lines as $line) {
            if (strlen($line) === 0) {
            	if (count($current) != 7) {
            		throw new Exception("not the right size");
            	}

                if ($current[0] == '#####') {
                    $this->locks[] = $this->getHeights($current);
                }
                if ($current[6] == '#####') {
                    $this->keys[] = $this->getHeights($current);
                }

                echo implode(", ", $this->getHeights($current)) . "\n";
                print_r($current);

                $current = [];
                continue;
            }

            $current[] = $line;
        }
    }

    protected function getHeights($item)
    {
        $heights = [ 0, 0, 0, 0, 0 ];
        foreach ($item as $i => $row) {
            if (($i === 0 || $i === 6 && $row == '#####') || $row == '.....') {
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
