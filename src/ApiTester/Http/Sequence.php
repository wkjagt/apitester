<?php

namespace ApiTester\Http;

use ApiTester\Config\Config;
use ApiTester\Assertion\Exception as AssertionException;
use ApiTester\Assertion\Validator;

class Sequence
{
    protected $name;

    protected $variables;

    protected $client;

    protected $spec;

    protected $assertions;

    public function __construct(Config $spec, Config $globals,  Validator $validator)
    {
        $this->spec = $spec;
        $this->validator = $validator;
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
        return $this->validator->validateAll($expects, $response);
    }
}