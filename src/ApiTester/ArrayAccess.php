<?php

namespace ApiTester;

use Seagull;
use JsonSerializable;

class ArrayAccess implements JsonSerializable
{
    protected $array;

    public function __construct(array $raw)
    {
        $this->array = new Seagull($raw);
    }

    public function get($name = null)
    {
        return $this->array->get($name);
    }

    public function merge($toMerge)
    {
        $this->array->merge($toMerge);
    }

    public function set($key, $value)
    {
        $this->array->set($key, $value);
    }

    public function jsonSerialize()
    {
        return $this->array->get();
    }
}