<?php

namespace ApiTester\Validation;

class Success extends Result
{
    protected $actual;

    protected $additional;

    public function __construct($actual, $expected, array $additional = [])
    {
        $this->actual = $actual;
        $this->additional = $additional;
    }

    public function getActual()
    {
        return $this->actual;
    }
}