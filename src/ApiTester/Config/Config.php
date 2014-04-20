<?php

namespace ApiTester\Config;

use Seagull;

class Config
{
    protected $config = array();

    public function __construct(array $raw)
    {
        $this->config = new Seagull($raw);
    }

    public function get($name = null)
    {
        return $this->config->get($name);
    }

    public function merge($toMerge)
    {
        $this->config->merge($toMerge);
    }

    public function set($key, $value)
    {
        $this->config->set($key, $value);
    }
}