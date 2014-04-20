<?php

namespace ApiTester\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use ApiTester\TestEnvironment;

class RunCommand extends Command
{
    protected $variableClasses = array(
        'random_string' => '\\ApiTester\\Variable\\RandomString',
    );

    protected $assertionClasses = array(
        'status_code' => '\\ApiTester\\Assertion\\StatusCode',
        'format' => '\\ApiTester\\Assertion\\Format',
        'json_values' => '\\ApiTester\\Assertion\\JsonValues',
    );

    protected function configure()
    {
        $this
            ->setName('apitester:runall')
            ->setDescription('Run all tests from a config file')
            ->addArgument(
                'config-file',
                null,
                InputArgument::REQUIRED,
                ''
            )
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // setup application
        $env = new TestEnvironment(
            new \ApiTester\Variable\Replacer($this->variableClasses),
            new \ApiTester\Assertion\Validator($this->assertionClasses)
        );

        $env->registerConfigFileLoader(new \ApiTester\Config\YamlFileLoader);
        $env->loadConfigFile($input->getArgument('config-file'));

        $env->runAll();
    }
}