<?php

namespace ApiTester\Config;

interface ValidatorInterface
{
    public function validate(array $array);
}