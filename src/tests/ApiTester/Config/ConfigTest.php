<?php

use ApiTester\Config\YamlFileLoader;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testLoadFile()
    {
        $fl = new YamlFileLoader;
        $fl->load(__DIR__.'/../../config.yml');
        $parsed = $fl->getRawConfig();

        // not testing all values because we have the symfony yaml parser tests for that
        // just checking if it parsed something by checking if it's a non empty array
        $this->assertInternalType('array', $parsed);
        $this->assertNotEmpty($parsed);
    }

    public function testGetConfig()
    {
        $fl = new YamlFileLoader;
        $fl->load(__DIR__.'/../../config.yml');
        $config = $fl->getConfig();

        $this->assertInstanceOf('ApiTester\Config\Config', $config);
    }
}