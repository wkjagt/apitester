<?php

namespace ApiTester\Config;

use Symfony\Component\Yaml\Parser;

class YamlFileLoader extends Parser implements FileLoaderInterface
{
    protected $configs= array();

    protected $parsed = array();

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
        return new Config($this->getRawConfig());
    }

}