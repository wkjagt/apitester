<?php

namespace ApiTester;

use ApiTester\Config\FileLoaderInterface;
use ApiTester\ArrayAccess;
use ApiTester\Connection\Sequence;
use ApiTester\Connection\Client;
use ApiTester\Variable\Replacer;
use ApiTester\Validation\Validator;

class TestEnvironment
{
    protected $configFileLoader = null;

    protected $globalConfig = null;

    protected $replacer;

    protected $validator;

    public function __construct(Replacer $replacer, Validator $validator)
    {
        $this->replacer = $replacer;
        $this->validator = $validator;
    }

    public function registerConfigFileLoader(FileLoaderInterface $configFileLoader)
    {
        $this->configFileLoader = $configFileLoader;
    }

    public function loadConfigFile($filePath)
    {
        $this->configFileLoader->load($filePath);
        $this->globalConfig = $this->configFileLoader->getConfig();
    }

    public function runall()
    {
        $specs = $this->globalConfig->get('sequences');
        foreach($specs as &$spec) {

            $spec['variables'] = $this->replacer->replaceAll($spec['variables']);
            $spec['requests'] = $this->setVariables($spec['requests'], $spec['variables']);

            $globals = new ArrayAccess($this->globalConfig->get('globals'));
            $sequence = new Sequence(new ArrayAccess($spec), $globals, $this->validator);
            $errors = $sequence->run();
        }
    }

    public function setVariables(array $requests, array $variables)
    {
        array_walk_recursive($requests, function(&$item, $key, $variables)
        {
            $item = preg_replace_callback('/%(\w+)%/', function($matches) use ($variables)
            {
                return isset($variables[$matches[1]]) ? $variables[$matches[1]] : $matches[0];
            }, $item);
        }, $variables);

        return $requests;
    }

}
