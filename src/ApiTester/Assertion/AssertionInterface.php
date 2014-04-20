<?php

namespace ApiTester\Assertion;

interface AssertionInterface
{
    public function validate($response, $value);
}