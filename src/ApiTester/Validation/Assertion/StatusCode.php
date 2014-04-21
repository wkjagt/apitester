<?php

namespace ApiTester\Validation\Assertion;

class StatusCode implements AssertionInterface
{
    public function validate($response, $expected)
    {
        $actual = $response->getStatusCode();
        if($actual != $expected) {
            return [new Error($actual, $expected)];
        }
    }
}