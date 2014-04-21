<?php

namespace ApiTester\Validation\Assertion;

use ApiTester\Config\Config;
use ApiTester\Validation\Error;

class JsonValues implements AssertionInterface
{
    public function validate($response, $values)
    {
        $body = $response->getBody()->__toString();
        $bodyValues = new Config(json_decode($body, true));

        $errors = [];

        foreach($values as $key => $expected) {
            $actual = $bodyValues->get($key);

            if($actual != $expected) {
                $errors[] = new Error($expected, $actual, ['key' => $key]);
            }
        }
        return $errors;
    }
}