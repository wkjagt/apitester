<?php

namespace ApiTester\Validation;

use ApiTester\Validation\ResultCollector;

class Validator
{
    protected $assertions = array();

    public function __construct(array $assertionClasses = array(), ResultCollector $resultCollector)
    {
        foreach($assertionClasses as $name => $class) {
            $this->assertions[$name] = new $class;
        }
        $this->resultCollector = $resultCollector;
    }

    public function validateAll($expects, $response, $sequenceName, $requestName)
    {
        foreach($expects as $assertionName => $value) {

            if(isset($this->assertions[$assertionName])) {
                $assertionErrors = $this->assertions[$assertionName]->validate($response, $value);

                if($assertionErrors) {
                    foreach($assertionErrors as $error) {
                        $this->resultCollector->add($error, $sequenceName, $requestName);
                    }
                }
            } else {
                throw new \Exception('Invalid assertion : ' . $assertionName);
            }
        }
    }

}