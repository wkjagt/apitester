<?php

namespace ApiTester\Validation\Assertion;

use ApiTester\Config\Config;
use ApiTester\Validation\Error;

class JsonValues extends Values
{
    public function validate($response, $values)
    {
        $body = $response->getBody()->__toString();
        $bodyValues = new Config(json_decode($body, true));
        return $this->validator->validate($bodyValues, $values);
    }
}