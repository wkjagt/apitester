<?php

namespace ApiTester\Validation\Assertion\ValueValidationFunction;

class Equals
{
    public function validate($actual, $expected)
    {
        return $actual == $expected;
    }
}