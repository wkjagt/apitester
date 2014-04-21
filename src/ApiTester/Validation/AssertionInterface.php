<?php

namespace ApiTester\Validation;

interface AssertionInterface
{
    public function validate($response, $value);
}