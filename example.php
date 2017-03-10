#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Benchmark\Benchmark;
use Benchmark\BenchmarkReport;

function compareBenchmarks(BenchmarkReport $a, BenchmarkReport $b)
{
    if ($a->getTimePerOp() > $b->getTimePerOp()) {
        $ratio = $a->getTimePerOp() / $b->getTimePerOp();
        echo sprintf('"%s" is %s time faster than %s', $b->getName(), $ratio, $a->getName()) . PHP_EOL;
    } else {
        $ratio = $b->getTimePerOp() / $a->getTimePerOp();
        echo sprintf('"%s" is %s time faster than %s', $a->getName(), $ratio, $b->getName()) . PHP_EOL;
    }
}

$present = function (BenchmarkReport $report) {
    $ms = $report->getTime() * 1000;
    echo sprintf(
        '"%s" executed %s times in %s ms (%s ms / op)',
        $report->getName(),
        $report->getN(),
        $ms,
        $ms / $report->getN()
    ) . PHP_EOL;
};

$echo = Benchmark::run(function ($b) {
    for ($i = 0; $i < $b->getN(); $i++) {
        echo "Something";
    }
}, 'echo');
$present($echo);

$print = Benchmark::run(function ($b) {
    for ($i = 0; $i < $b->getN(); $i++) {
        print("Something");
    }
}, 'print');
$present($print);

compareBenchmarks($echo, $print);

$sleep = Benchmark::run(function ($b) {
    sleep(2);

    // You can reset the timer in case of heavy bootstraping code
    $b->reset();
    for ($i = 0; $i < $b->getN(); $i++) {
        echo "Something";
    }
}, 'echo with bootstraping code');
$present($sleep);
