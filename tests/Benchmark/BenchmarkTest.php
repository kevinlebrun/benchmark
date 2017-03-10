<?php

namespace Tests\Benchmark;

use Benchmark\Benchmark;
use Benchmark\BenchmarkReport;

class BenchmarkTest extends \PHPUnit_Framework_TestCase
{
    public function testBenchmarkRunnerShouldReportBenchmarkRun()
    {
        $hasBeenRan = false;
        $report = Benchmark::run(function () use (&$hasBeenRan) {
            $hasBeenRan = true;
        }, 'My Benchmark', 100);

        $this->assertInstanceOf(BenchmarkReport::class, $report);

        $this->assertEquals('My Benchmark', $report->getName());
        $this->assertEquals(100, $report->getN());
        $this->assertTrue($report->getTime() < 1, 'Empty benchmark should run in less than 1 ms');
        $this->assertTrue($report->getTimePerOp() < 1, 'Empty benchmark operation should run in less than 1 ms');

        $this->assertTrue($hasBeenRan, 'Benchmark should have been ran');
    }

    public function testBenchmarkShouldHideOutput()
    {
        ob_start();
        $report = Benchmark::run(function () use (&$hasBeenRan) {
            echo 'coucou';
        }, 'My Benchmark');
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals('', $output);
    }

    public function testBenchmarkShouldDiscardHeavyInit()
    {
        $report = Benchmark::run(function ($b) {
            usleep(2000);
            $b->reset();
        }, 'My Benchmark');

        $this->assertTrue($report->getTime() * 1000 < 1, 'Empty benchmark should run in less than 1 ms');
        $this->assertTrue($report->getTimePerOp() * 1000 < 1, 'Empty benchmark operation should run in less than 1 ms');
    }

    public function testBenchmarkIterationsNumberIsPassedToClosure()
    {
        $n = 0;
        $report = Benchmark::run(function ($b) use (&$n) {
            $n = $b->getN();
        }, 'My Benchmark', 500);

        $this->assertEquals(500, $n);
    }
}
