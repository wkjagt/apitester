<?php

namespace ApiTester\Validation\Assertion;

use ApiTester\Config\Config;
use ApiTester\Validation\Error;

abstract class Values implements AssertionInterface
{
    protected $validator;

    public function __construct(ValueValidator $validator)
    {
        $this->validator = $validator;
    }

    abstract public function validate($response, $values);

}