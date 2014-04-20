<?php

use ApiTester\TestEnvironment;

class EnvironmentTest extends PHPUnit_Framework_TestCase
{
    public function testInstantiate()
    {
        $env = new TestEnvironment;
    }

    public function testRegisterFileLoader()
    {
        $env = new TestEnvironment;
        $stub = $this->getMock("ApiTester\Config\YamlFileLoader");
        $env->registerConfigFileLoader($stub);
    }

    public function testLoadConfigs()
    {
        $configPath = __DIR__.'/../config.yml';

        $env = new TestEnvironment;
        $stub = $this->getMock("ApiTester\Config\YamlFileLoader");
        $stub->expects($this->once())
             ->method('load')
             ->with($this->equalTo($configPath));

        $env->registerConfigFileLoader($stub);
        $env->loadConfigFile($configPath);
    }

}