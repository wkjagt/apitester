<?php

namespace ApiTester;

use ApiTester\Config\FileLoaderInterface;
use ApiTester\Config\Config;
use ApiTester\Http\Sequence;
use ApiTester\Http\Client;

class TestEnvironment
{
    protected $configFileLoader = null;

    protected $globalConfig = null;

    protected $assertions = [];

    protected $assertionClasses = array(
        'status_code' => '\\ApiTester\\Assertion\\StatusCode',
        'format' => '\\ApiTester\\Assertion\\Format',
        'json_values' => '\\ApiTester\\Assertion\\JsonValues',
    );

    public function __construct()
    {
        foreach($this->assertionClasses as $name => $class) {
            $this->assertions[$name] = new $class;
        }
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
        foreach($this->getSequences() as $sequence) {
            $errors = $sequence->run();
            echo '<pre>file: '.__FILE__."\nline: ".__LINE__."\n".print_r($errors, true).'</pre>';die;
        }
    }

    public function getSequences()
    {
        $specs = $this->globalConfig->get('sequences');
        foreach($specs as &$spec) {

            $spec['variables'] = $this->replaceVariables($spec['variables']);
            $spec['requests'] = $this->setVariables($spec['requests'], $spec['variables']);

            $globals = new Config($this->globalConfig->get('globals'));
            $sequences[] = new Sequence(new Config($spec), $globals, $this->assertions);
        }
        return $sequences;
    }

    // todo: make this a plugin system
    public function replaceVariables($variables)
    {
        foreach($variables as $key => &$value) {
            if(preg_match('/\%(\w+)\%/', $value, $matches)) {
                switch($matches[1]) {
                    case 'random_string':
                        $value = bin2hex(openssl_random_pseudo_bytes(40));
                        break;
                    default:
                        throw new \Exception('Invalid function : '. $matches[1]);
                }
            }
        }
        return $variables;
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
