<?php

namespace ApiTester\Validation\Assertion;

interface AssertionInterface
{
    public function validate($response, $value);
}