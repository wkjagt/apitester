<?php

namespace ApiTester\Validation\Assertion;

use ApiTester\Validation\Error;

class ResponseFormatAssertion implements AssertionInterface
{
    public function validate($response, $value)
    {
        switch($value) {
            case 'json':
                $body = $response->getBody()->__toString();
                $jsonDecoded = json_decode($body);
                if(json_last_error()) {
                    return [new Error('unknown', $value, ['body' => $body])];
                }
                break;
        }

    }
}