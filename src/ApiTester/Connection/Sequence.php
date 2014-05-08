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
        $this->client = new Client($globals->get('request') ?: array());
    }

    public function run()
    {
        $errors = [];
        $responses = [];

        foreach($this->spec->get('requests') as $requestDetails) {

            $requestDetails = $this->populateFromPreviousResponse($requestDetails, $responses);

            $details = new ArrayAccess($requestDetails);
            $request = new Request($this->client, $details, $this->variables);

            $response = $request->run();
            $responses[] = $response;

            // echo $response->getBody();
            if($expects = $details->get('expects_response')) {
                $this->validator->validateAll($expects, $response, $this->name, $requestDetails['name']);                
            }
        }
        return $errors;
    }

    // todo: this doesn't belong here
    protected function populateFromPreviousResponse($requestDetails, array $responses)
    {
        array_walk_recursive($requestDetails, function(&$item, $key, $responses)
        {
            $pattern = '/%responses\.(?P<index>-?\d+)\.(?P<format>\w+)_values\.(?P<key>[a-zA-Z0-9-_\.]+)%/';

            // $pattern = '/%previous_request\.(?P<format>\w+)_data\.(?P<key>[a-zA-Z0-9-_\.]+)%/';
            $item = preg_replace_callback($pattern, function($matches) use ($responses)
            {
                $index = $matches['index'];

                if($index < 0) {
                    // counting from the back
                    $index += count($responses);
                }

                if(!isset($responses[$index])) {
                    throw new \Exception(
                        sprintf('Out of range response index used in variable "%s".', $matches[0]));
                }
                $body = (string) $responses[$index]->getBody();

                // todo make this more flexible
                switch($matches['format']) {
                    case 'json':
                        $data = new ArrayAccess(json_decode($body, true));
                        return $data->get($matches['key']);
                }
            }, $item);
        }, $responses);

        return $requestDetails;
    }

}