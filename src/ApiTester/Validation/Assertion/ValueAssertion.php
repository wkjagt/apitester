<?php

namespace ApiTester\Validation\Assertion;

use ApiTester\ArrayAccess;
use ApiTester\Validation\Error;

abstract class ValueAssertion implements AssertionInterface
{
    // const PATTERN = '/^\((?P<function>\w+)\:(?P<value>.+)\)$/';

    protected $functions = [];

    public function __construct(array $functions)
    {
        $this->functions = $functions;
    }

    abstract public function validate($response, $values);

    protected function runFunctions($bodyValues, $values)
    {
        $errors = [];

        foreach($values as $key => $rule) { 
            $function = isset($rule['function']) ? $rule['function'] : 'equals';
            $expected = $rule['value'];

            if (isset($this->functions[$function])) {
                $actual = $bodyValues->get($key);
                if(!$this->functions[$function]->validate($actual, $expected)) {
                    $errors[] = new Error($expected, $actual, ['key' => $key]);
                }

            } else {
                throw new \Exception('Function not found: ' . $function);
            }
        }
        return $errors;

    }

}