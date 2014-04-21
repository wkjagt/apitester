<?php

namespace ApiTester\Validation;

class ErrorCollector
{
    protected $output;

    protected $errors = [];

    public function __construct($output)
    {
        $this->output = $output;
    }

    public function add(Error $error, $sequenceName, $requestName)
    {
        $this->errors[] = $error;

        $msg = 'Error for request "%s" in sequence "%s". Got "%s" instead of "%s"';
        $msg = sprintf($msg,
            $requestName,
            $sequenceName,
            $error->getActual(),
            $error->getExpected()
        );
        $this->output->writeln($msg);
    }
}