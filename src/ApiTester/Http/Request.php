<?php

namespace ApiTester\Http;

use ApiTester\Config\Config;

class Request
{
    protected $requestDetails;

    protected $variables;

    protected $client;

    public function __construct(Client $client, Config $requestDetails, Config $variables)
    {
        $this->client = $client;
        $this->requestDetails = $requestDetails;
        $this->variables = $variables;
    }

    public function run()
    {
        $details = $this->requestDetails;
        $variables = $this->variables;

        $url = $details->get('url');
        $method = $details->get('method');

        $options = ['exceptions' => false];

        if(in_array($method, ['POST', 'PUT']) && $data = $details->get('data')) {
            switch($details->get('data.format')) {
                case 'url_encoded':
                    $options['body'] = http_build_query($details->get('data.values'));
                    break;
            }
        }
        $request = $this->client->createRequest($method, $url, $options);
        return $this->client->send($request);
    }


}