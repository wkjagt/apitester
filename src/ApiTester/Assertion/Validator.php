<?php

namespace ApiTester\Assertion;

use ApiTester\Assertion\ErrorCollector;

class Validator
{
    protected $assertions = array();

    public function __construct(array $assertionClasses = array(), ErrorCollector $errorCollector)
    {
        foreach($assertionClasses as $name => $class) {
            $this->assertions[$name] = new $class;
        }
        $this->errorCollector = $errorCollector;
    }

    public function validateAll($expects, $response, $sequenceName, $requestName)
    {
        foreach($expects as $assertionName => $value) {

            if(isset($this->assertions[$assertionName])) {
                $assertionErrors = $this->assertions[$assertionName]->validate($response, $value);

                if($assertionErrors) {
                    foreach($assertionErrors as $error) {
                        $this->errorCollector->add($error, $sequenceName, $requestName);
                    }
                }
            } else {
                throw new \Exception('Invalid assertion : ' . $assertionName);
            }
        }
    }

}