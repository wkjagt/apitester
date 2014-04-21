<?php

namespace ApiTester\Config;

use Symfony\Component\Yaml\Parser;
use ApiTester\ArrayAccess;

class YamlFileLoader extends Parser implements FileLoaderInterface
{
    protected $configs= [];

    protected $parsed = [];

    public function load($filePath)
    {
        $contents = file_get_contents($filePath);
        $this->parsed = $this->parse($contents);
    }

    public function getRawConfig()
    {
        return $this->parsed;
    }

    public function getConfig()
    {
        return new ArrayAccess($this->getRawConfig());
    }

}