<?php

namespace ApiTester\Variable\Callback;

class RandomString
{
    public function replace($placeholder)
    {
        return bin2hex(openssl_random_pseudo_bytes(40));
    }
}