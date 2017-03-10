<?php

namespace Benchmark;

class Benchmark
{
    protected $n;
    protected $start;

    public function __construct($n)
    {
        $this->n = $n;
    }

    public function reset()
    {
        if ($this->start) {
            ob_end_clean();
        }
        ob_start();
        $this->start = microtime(true);
    }

    public function exec($f)
    {
        $f($this);
    }

    public function report($name)
    {
        $time = microtime(true) - $this->start;
        ob_end_clean();

        return new BenchmarkReport($this->n, $name, $time);
    }

    public function getN()
    {
        return $this->n;
    }

    public static function run($f, $name, $n = 1000)
    {
        $b = new Benchmark($n, $name);
        $b->reset();
        $b->exec($f);
        return $b->report($name);
    }
}
