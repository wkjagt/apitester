<?php

namespace ApiTester\Validation;

use ApiTester\Config\Config;

class JsonValues implements AssertionInterface
{
    public function validate($response, $values)
    {
        $body = $response->getBody()->__toString();
        $bodyValues = new Config(json_decode($body, true));

        $errors = [];

        foreach($values as $value) {
            $actual = $bodyValues->get($value['key']);
            $expected = $value['value'];

            if($actual != $expected) {
                $errors[] = new Error($expected, $actual, ['key' => $value['key']]);
            }
        }
        return $errors;
    }
}