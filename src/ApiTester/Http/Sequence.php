<?php

namespace ApiTester\Http;

use ApiTester\Config\Config;
use ApiTester\Assertion\Exception as AssertionException;

class Sequence
{
    protected $name;

    protected $requests = array();

    protected $variables;

    protected $client;

    protected $spec;

    protected $assertions;

    public function __construct(Config $spec, Config $globals, array $assertions)
    {
        $this->spec = $spec;
        $this->assertions = $assertions;
        $this->name = $spec->get('name');
        $this->variables = new Config($spec->get('variables'));

        $this->setClient($globals);
    }


    /**
     * Setup a http client using the globals
     */
    public function setClient($globals)
    {        
        $options = ['base_url' => $globals->get('request.base_url')];

        if($defaults = $globals->get('request.defaults')) {
            $options['defaults'] = $defaults;
        }
        $this->client = new Client($options);
    }

    public function run()
    {
        $errors = [];

        foreach($this->spec->get('requests') as $requestDetails) {

            $details = new Config($requestDetails);
            $request = new Request($this->client, $details, $this->variables);

            $response = $request->run();

            if($requestErrors = $this->validateResponse($details, $response)) {
                $errors[$details->get('name')] = $requestErrors;
            }
        }
        return $errors;
    }


    public function validateResponse($details, $response)
    {
        if(!$expects = $details->get('expects_response')) {
            return;
        }

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