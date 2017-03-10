<?php

namespace Benchmark;

class BenchmarkReport
{
    protected $n;
    protected $name;
    protected $time;

    public function __construct($n, $name, $time)
    {
        $this->n = $n;
        $this->name = $name;
        $this->time = $time;
    }

    public function getN()
    {
        return $this->n;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getTimePerOp()
    {
        return $this->time / $this->n;
    }

    public function getName()
    {
        return $this->name;
    }
}
