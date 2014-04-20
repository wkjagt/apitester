<?php

namespace ApiTester\Assertion;

class Validator
{
    protected $assertions = array();

    public function __construct(array $assertionClasses = array())
    {
        foreach($assertionClasses as $name => $class) {
            $this->assertions[$name] = new $class;
        }
    }

    public function validateAll($expects, $response)
    {
        $errors = [];

        foreach($expects as $assertionName => $value) {

            if(isset($this->assertions[$assertionName])) {
                $assertionErrors = $this->assertions[$assertionName]->validate($response, $value);

                if($assertionErrors) {
                    $errors[$assertionName] = $assertionErrors;
                }
            } else {
                throw new \Exception('Invalid assertion : ' . $assertionName);
            }
        }
        return $errors;
    }

}