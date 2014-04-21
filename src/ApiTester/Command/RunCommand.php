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
    protected $variableClasses = [
        'random_string' => '\\ApiTester\\Variable\\RandomString',
    ];

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
        $valueFunctions = [
            'equals' => new \ApiTester\Validation\Assertion\ValueValidationFunction\Equals
        ];
        
        $valueValidator = new \ApiTester\Validation\Assertion\ValueValidator($valueFunctions);

        $assertions = [
            'status_code' => new \ApiTester\Validation\Assertion\StatusCode,
            'format' => new \ApiTester\Validation\Assertion\Format,
            'json_values' => new \ApiTester\Validation\Assertion\JsonValues($valueValidator),
        ];        

        $resultCollector = new \ApiTester\Validation\ResultCollector($output);

        // setup application
        $env = new TestEnvironment(
            new \ApiTester\Variable\Replacer($this->variableClasses),
            new \ApiTester\Validation\Validator($assertions, $resultCollector)
        );

        $env->registerConfigFileLoader(new \ApiTester\Config\YamlFileLoader);
        $env->loadConfigFile($input->getArgument('config-file'));

        $env->runAll();
    }
}