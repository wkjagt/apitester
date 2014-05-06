<?php

namespace ApiTester\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use ApiTester\TestEnvironment;

use ApiTester\Validation\Assertion\StatusCodeAssertion;
use ApiTester\Validation\Assertion\ResponseFormatAssertion;
use ApiTester\Validation\Assertion\JsonValueAssertion;


class RunCommand extends Command
{
    protected $variableClasses = [
        'random_string' => '\\ApiTester\\Variable\\Callback\\RandomString',
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
        
        $assertions = [
            'status_code' => new StatusCodeAssertion,
            'format' => new ResponseFormatAssertion,
            'json_values' => new JsonValueAssertion($valueFunctions),
        ];        

        $resultCollector = new \ApiTester\Validation\ResultCollector($output);

        // setup application
        $env = new TestEnvironment(
            new \ApiTester\Variable\Replacer($this->variableClasses),
            new \ApiTester\Validation\Validator($assertions, $resultCollector)
        );

        $configValidator = new \ApiTester\Config\Validator(__DIR__.'/../../../schema.json', $output);

        $env->registerConfigFileLoader(new \ApiTester\Config\YamlFileLoader($configValidator));
        $env->loadConfigFile($input->getArgument('config-file'));

        $env->runAll();
    }
}