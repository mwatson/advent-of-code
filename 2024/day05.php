<?php

$lines = explode("\n", file_get_contents(__DIR__ . "/day05.txt"));

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

$combined = [];
foreach ($rules as $rule) {
    [ $x, $y ] = explode('|', $rule);
    if (empty($combined[$x])) {
        $combined[$x] = [];
    }

    $combined[$x][$y] = true;
}

//print_r($combined);

foreach ($jobs as $job) {
    foreach ($job as $page) {
        echo "{$page} ";
    }
    echo "\n";

    break;
}

