<?php

require __DIR__.'/vendor/autoload.php';

$console = new Symfony\Component\Console\Application();
$console->add(new ApiTester\Command\RunCommand);
$console->run();








// $env = new ApiTester\Environment;

// $env->registerConfigFileLoader(new ApiTester\Config\YamlFileLoader);
// $env->loadConfigFile($fileName);


// $env->runAll();



// ApiTester
//     Environment
//     Command
//         RunCommand
//     Config
//         FileLoaderInterface
//         YamlFileLoader
//         Reader
//     TestElement
//         Sequence
//         Test
//     Function
//         RandomString
//     Connection
//         ConnectionInterface
//         HttpConnection






















// require __DIR__.'/vendor/autoload.php';

// $apiSpecYaml = file_get_contents(__DIR__.'/tests.yml');
// $tests = Symfony\Component\Yaml\Yaml::parse($apiSpecYaml);


// print_r($tests);