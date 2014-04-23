<?php

namespace ApiTester\Validation\Assertion;

use ApiTester\Validation\Error;

class StatusCodeAssertion implements AssertionInterface
{
    public function validate($response, $expected)
    {
        $actual = $response->getStatusCode();
        if($actual != $expected) {
            return [new Error($actual, $expected)];
        }
    }
}