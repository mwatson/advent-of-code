<?php

class Day07 extends Day
{
    public function part1()
    {
        //$this->echoOn();
        return $this->runOperations(2);
    }

    public function part2()
    {
        //$this->echoOn();
        return $this->runOperations(3);
    }

    protected function runOperations($max)
    {
        $lines = explode("\n", $this->data);

        $total = 0;

        foreach ($lines as $line) {
            [ $result, $str ] = explode(": ", $line);
            $result = intval($result);
            $numbers = array_map('intval', explode(" ", $str));
            $operations = array_fill(0, count($numbers) - 1, 0);

            while (1) {
                $rowTotal = $numbers[0];
                for ($i = 1; $i < count($numbers); $i++) {
                    if ($operations[$i - 1] == 0) {
                        $rowTotal = $rowTotal * $numbers[$i];
                    } else if ($operations[$i - 1] == 1) {
                        $rowTotal = $rowTotal + $numbers[$i];
                    } else if ($operations[$i - 1] == 2) {
                        $rowTotal = intval("{$rowTotal}{$numbers[$i]}");
                    }

                    if ($rowTotal > $result) {
                        break;
                    }
                }

                if ($rowTotal == $result) {
                    $this->echo(
                        "Success! {$result} == ".
                        $this->makeEquation($numbers, $operations).
                        "\n"
                    );

                    $total += $result;
                    break;
                }

                $operations = $this->increase($operations, $max);
                // if we loop back to zero we've tried all options
                // need to figure out fastest way to check for array of all zeros
                /*
                if (empty(array_filter($operations))) {
                    $this->echo("FAILED {$result} [ " . implode(' ', $numbers) . " ] \n");
                    break;
                }
                */

                // this is slightly faster than filtering every loop
                $foundZero = true;
                foreach ($operations as $op) {
                    if ($op != 0) {
                        $foundZero = false;
                        break;
                    }
                }
                if ($foundZero) {
                    break;
                }

                /*
                if (array_all($operations, function($i) { return $i == 0; })) {
                    break;
                }
                */
            }
        }

        return $total;
    }

    // mirror counter 000 -> 100 -> 010 -> 110 -> etc
    protected function increase($ops, $max = 2, $i = 0)
    {
        if ($i >= count($ops)) {
            return $ops;
        }

        $ops[$i]++;
        if ($ops[$i] == $max) {
            $ops[$i] = 0;
            $ops = $this->increase($ops, $max, $i + 1);
        }

        return $ops;
    }

    protected function makeEquation($numbers, $operations)
    {
        $str = array_shift($numbers) . ' ';
        foreach ($numbers as $i => $number) {
            $op = '+';
            if ($operations[$i] == 1) $op = '*';
            if ($operations[$i] == 2) $op = '||';
            $str .= "{$op} {$number} ";
        }

        return $str;
    }
}
