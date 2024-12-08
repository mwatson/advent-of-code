<?php

class Day08 extends Day
{
    protected $nodes = [];

    protected $height;
    protected $width;

    public function part1()
    {
        //$this->echoOn();

        $lines = explode("\n", $this->data);

        $this->height = count($lines);
        $this->width = strlen($lines[0]);

        $this->echo("height: {$this->height} x width: {$this->width}\n");

        foreach ($lines as $y => $line) {
            for ($x = 0; $x < strlen($line); $x++) {
                $spot = $line[$x];
                if ($spot == '.') {
                    continue;
                }
                if (empty($this->nodes[$spot])) {
                    $this->nodes[$spot] = [];
                }
                $this->nodes[$spot][] = [ 'x' => $x, 'y' => $y ];
            }
        }

        $validAntiNodes = [];

        foreach ($this->nodes as $char => $nodes) {
            foreach ($nodes as $n => $node) {
                for ($i = $n + 1; $i < count($nodes); $i++) {
                    $nodeX = $node['x'] - $nodes[$i]['x'];
                    $nodeY = $node['y'] - $nodes[$i]['y'];

                    $antiNodes = [
                        [ $node['x'] + $nodeX, $node['y'] + $nodeY ],
                        [ $nodes[$i]['x'] - $nodeX, $nodes[$i]['y'] - $nodeY ],
                    ];

                    $this->echo(
                        "===\n".
                        "node1: {$node['x']}, {$node['y']}\n".
                        "node2: {$nodes[$i]['x']}, {$nodes[$i]['y']}\n".
                        "diff: {$nodeX}, {$nodeY}\n".
                        "antinode1: {$antiNodes[0][0]}, {$antiNodes[0][1]}\n".
                        "antinode2: {$antiNodes[1][0]}, {$antiNodes[1][1]}\n"
                    );

                    foreach ($antiNodes as $antiNode) {
                        if ($antiNode[0] >= 0 && $antiNode[0] < $this->width &&
                            $antiNode[1] >= 0 && $antiNode[1] < $this->height
                        ) {
                            // tracking anti-nodes this way since it's possible for them
                            // to occupy the same spot, which counts as a single anti-node
                            $validAntiNodes["{$antiNode[0]}-{$antiNode[1]}"] = true;
                        }
                    }
                }
            }
        }

        return count($validAntiNodes);
    }

    public function part2()
    {
        //$this->echoOn();

        $validAntiNodes = [];

        foreach ($this->nodes as $char => $nodes) {
            foreach ($nodes as $n => $node) {
                for ($i = $n + 1; $i < count($nodes); $i++) {
                    $nodeX = $node['x'] - $nodes[$i]['x'];
                    $nodeY = $node['y'] - $nodes[$i]['y'];

                    // nodes are anti-nodes? what a country!
                    $validAntiNodes["{$node['x']}-{$node['y']}"] = true;
                    $validAntiNodes["{$nodes[$i]['x']}-{$nodes[$i]['y']}"] = true;

                    // keep finding anti-nodes until we're out of bounds
                    $lastAntiX = $node['x'] + $nodeX;
                    $lastAntiY = $node['y'] + $nodeY;
                    $this->echo(
                        "{$lastAntiX} {$lastAntiY} ({$nodeX} {$nodeY})\n"
                    );
                    while (1) {
                        if ($lastAntiX < 0 || $lastAntiX >= $this->width ||
                            $lastAntiY < 0 || $lastAntiY >= $this->height
                        ) {
                            break;
                        }

                        $this->echo(
                            "  {$lastAntiX} {$lastAntiY}\n"
                        );

                        $validAntiNodes["{$lastAntiX}-{$lastAntiY}"] = true;

                        $lastAntiX += $nodeX;
                        $lastAntiY += $nodeY;
                    }

                    $lastAntiX = $nodes[$i]['x'] - $nodeX;
                    $lastAntiY = $nodes[$i]['y'] - $nodeY;
                    while (1) {
                        if ($lastAntiX < 0 || $lastAntiX >= $this->width ||
                            $lastAntiY < 0 || $lastAntiY >= $this->height
                        ) {
                            break;
                        }

                        $validAntiNodes["{$lastAntiX}-{$lastAntiY}"] = true;

                        $lastAntiX -= $nodeX;
                        $lastAntiY -= $nodeY;
                    }
                }
            }
        }

        return count($validAntiNodes);
    }
}
