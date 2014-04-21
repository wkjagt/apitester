<?php

namespace ApiTester\Validation;

class Error extends Result
{
    protected $actual;

    protected $expected;

    protected $additional;

    public function __construct($actual, $expected, array $additional = [])
    {
        $this->actual = $actual;
        $this->expected = $expected;
        $this->additional = $additional;
    }

    public function getActual()
    {
        return $this->actual;
    }

    public function getExpected()
    {
        return $this->expected;
    }

}
