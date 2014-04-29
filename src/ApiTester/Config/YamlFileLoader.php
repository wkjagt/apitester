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
        $fullPath = realpath($filePath);
        $this->testsDir = pathinfo($fullPath, PATHINFO_DIRNAME);

        $this->parsed = $this->parseFileByPath($fullPath);
        $this->doIncludes($this->parsed);
    }

    protected function parseFileByPath($filePath)
    {
        $contents = file_get_contents($filePath);
        return $this->parse($contents);
    }

    protected function doIncludes(&$parsed)
    {
        array_walk_recursive($parsed, function(&$item, $key)
        {
            if(preg_match('/@(?P<include>.+)/', $item, $matches)) {
                $include = sprintf('%s/%s', $this->testsDir, $matches['include']);
                $item = $this->parseFileByPath($include);
            }
        });
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