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
        $env = new TestEnvironment;

        $env->registerConfigFileLoader(new \ApiTester\Config\YamlFileLoader);
        $env->loadConfigFile($input->getArgument('config-file'));

        $env->runAll();
    }
}