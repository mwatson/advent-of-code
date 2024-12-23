<?php

class Day17 extends Day
{
    const ADV = 0;
    const BXL = 1;
    const BST = 2;
    const JNZ = 3;
    const BXC = 4;
    const OUT = 5;
    const BDV = 6;
    const CDV = 7;

    protected $register = [
        'A' => null,
        'B' => null,
        'C' => null,
    ];

    protected $instruction = 0;

    protected $program = [];

    protected $output = [];

    public function part1()
    {
        $this->echoOn();

        $this->init();

        while ($this->instruction < count($this->program)) {
            $this->exec();
        }

        return implode(',', $this->output);
    }

    public function part2()
    {
        //$this->echoOn();

        // 2,4,1,1,7,5,4,7,1,4,0,3,5,5,3,0

        /*

        bst 4 {"A":30553366,"B":0,"C":0}
        bxl 1 {"A":30553366,"B":6,"C":0}
        cdv 5 {"A":30553366,"B":7,"C":0}
        bxc 7 {"A":30553366,"B":7,"C":238698}
        bxl 4 {"A":30553366,"B":238701,"C":238698}
        adv 3 {"A":30553366,"B":238697,"C":238698}
        out 5 {"A":3819170,"B":238697,"C":238698}


        bst 4 -- regA % 8 = [some value], put in regB
        bxl 1 -- regB ^= 1 --> bit flip rightmost bit of regB (store in regB)
        cdv 5 -- regA / (2^regB) -> into regC
        bxc 7 -- regB = regB ^ regC, into regC
        bxl 4 -- regB = regB ^ 4 -- XXXXXXXX ^ 00000100 (always flip 3rd bit from right in regB)
                    -- 1100100 ^ 0000100 = 1100000 | 1100011 ^ 0000100 = 1100111
        adv 3 -- regA = regA / (2^3) -- always divide regA / 8, copy to regA
        out 5 -- regB % 8

        loop
        */

        // reverse engineer
        // regA only gets / 8 at the end, 16 outputs needed meaning the minimum is 
        // 281474976710656 (???)
        // last output is 0


        //return 0;
        
        // brute force (this won't work)

        $a = 281474976710656;

        while (1) {
            $this->init();
            $this->register['A'] = $a;

            while ($this->instruction < count($this->program)) {
                $this->exec();

                $match = true;
                if (count($this->output)) {
                    foreach ($this->output as $i => $out) {
                        if ($out !== $this->program[$i]) {
                            $match = false;
                            break;
                        }
                    }

                    if ($match && count($this->output) == count($this->program)) {
                        return $a;
                    }
                }

                if (!$match) {
                    break;
                }
            }

            $a++;
        }

        return $a;
    }

    protected function init()
    {
        $lines = explode("\n", $this->data);

        $matches = [];

        foreach ($lines as $line) {
            if (preg_match('/^Register (A|B|C): ([0-9]+)$/', $line, $matches)) {
                [ $m, $reg, $val ] = $matches;
                $this->register[$reg] = (int) $val;
            }
            if (preg_match('/^Program: ([0-9,]+)$/', $line, $matches)) {
                $this->program = array_map('intval', explode(',', $matches[1]));
            }
        }

        $this->instruction = 0;
        $this->output = [];
    }

    protected function exec()
    {
        $opcode = $this->program[$this->instruction];
        $operand = $this->program[$this->instruction + 1];

        $increment = 2;

        //echo "{$opcode} {$operand}\n";

        switch ($opcode) {
            case self::ADV:
                $this->debug("adv {$operand}");
                $this->divide($operand, 'A');
                break;
            case self::BXL:
                $this->debug("bxl {$operand}");
                $val = $this->register['B'] ^ $operand;
                $this->register['B'] = $val;
                break;
            case self::BST:
                $this->debug("bst {$operand}");
                $this->register['B'] = $this->combo($operand) % 8;
                break;
            case self::JNZ:
                if ($this->register['A'] !== 0) {
                    $this->debug("jnz {$operand}");
                    $this->instruction = $operand;
                    $increment = 0;
                }
                break;
            case self::BXC:
                $this->debug("bxc {$operand}");
                $val = $this->register['B'] ^ $this->register['C'];
                $this->register['B'] = $val;
                break;
            case self::OUT:
                $this->debug("out {$operand}");
                $this->output[] = $this->combo($operand) % 8;
                break;
            case self::BDV:
                $this->debug("bdv {$operand}");
                $this->divide($operand, 'B');
                break;
            case self::CDV:
                $this->debug("cdv {$operand}");
                $this->divide($operand, 'C');
                break;
            default:
                throw new Exception("Invalid opcode");
        }

        $this->instruction += $increment;
    }

    protected function divide($operand, $reg)
    {
        $exp = $this->combo($operand);
        $val = floor($this->register['A'] / pow(2, $exp));
        $this->register[$reg] = (int) $val;
    }

    protected function combo($operand)
    {
        if ($operand >= 0 && $operand <= 3) {
            return $operand;
        }

        if ($operand == 4) {
            return $this->register['A'];
        }

        if ($operand == 5) {
            return $this->register['B'];
        }

        if ($operand == 6) {
            return $this->register['C'];
        }

        if ($operand == 7) {
            throw new Exception("Invalid combo operand");
        }
    }

    protected function debug($msg)
    {
        $this->echoLine(
            "{$this->instruction} {$msg} " . json_encode($this->register)
        );
    }
}
