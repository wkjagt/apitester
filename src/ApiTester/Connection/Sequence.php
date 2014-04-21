<?php

namespace ApiTester\Connection;

use ApiTester\ArrayAccess;
use ApiTester\Validation\Exception as AssertionException;
use ApiTester\Validation\Validator;

class Sequence
{
    protected $name;

    protected $variables;

    protected $client;

    protected $spec;

    protected $assertions;

    public function __construct(ArrayAccess $spec, ArrayAccess $globals, Validator $validator)
    {
        $this->spec = $spec;
        $this->validator = $validator;
        $this->name = $spec->get('name');
        $this->variables = new ArrayAccess($spec->get('variables'));

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

            $details = new ArrayAccess($requestDetails);
            $request = new Request($this->client, $details, $this->variables);

            $response = $request->run();
            if($expects = $details->get('expects_response')) {
                $this->validator->validateAll($expects, $response, $this->name, $requestDetails['name']);                
            }
        }
        return $errors;
    }
}