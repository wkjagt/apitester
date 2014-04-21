<?php

namespace ApiTester\Validation;

use ApiTester\Validation\ResultCollector;

class Validator
{
    protected $assertions = [];

    public function __construct(array $assertionClasses = [], ResultCollector $resultCollector)
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
                // echo $assertionName;
                $assertionErrors = $this->assertions[$assertionName]->validate($response, $value);

                if($assertionErrors) {
                    $this->resultCollector->addErrors($assertionErrors, $assertionName, $sequenceName, $requestName);
                } else {
                    $this->resultCollector->addSuccess($assertionName, $sequenceName, $requestName);
                }
            } else {
                throw new \Exception('Invalid assertion : ' . $assertionName);
            }
        }
    }

}