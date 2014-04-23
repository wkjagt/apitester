<?php

namespace ApiTester\Validation\Assertion;

use ApiTester\ArrayAccess;
use ApiTester\Validation\Error;

class JsonValueAssertion extends ValueAssertion
{
    public function validate($response, $values)
    {
        $body = $response->getBody()->__toString();
        $bodyValues = new ArrayAccess(json_decode($body, true));
        return $this->runFunctions($bodyValues, $values);
    }
}