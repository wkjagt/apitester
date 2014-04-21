<?php

namespace ApiTester\Validation\Assertion;

use ApiTester\Config\Config;
use ApiTester\Validation\Error;

class ValueValidator
{
    const PATTERN = '/\((?P<function>\w+)\:(?P<value>\w+)\)/';

    protected $functions = [];

    public function __construct(array $functions)
    {
        $this->functions = $functions;
    }

    public function validate($bodyValues, $values)
    {
        $errors = [];

        foreach($values as $key => $expected) {

            if(preg_match(self::PATTERN, $expected, $matches)) {
                $function = $matches['function'];
                $expected = $matches['value'];

                if (isset($this->functions[$function])) {
                    $actual = $bodyValues->get($key);
                    if(!$this->functions[$function]->validate($actual, $expected)) {
                        $errors[] = new Error($expected, $actual, ['key' => $key]);
                    }

                } else {
                    throw new \Exception('Function not found: ' . $function);
                }
            }
        }
        return $errors;
    }

}