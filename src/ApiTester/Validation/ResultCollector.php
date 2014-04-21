<?php

namespace ApiTester\Validation;

class ResultCollector
{
    protected $output;

    protected $errors = [];

    public function __construct($output)
    {
        $this->output = $output;
    }

    public function addError(Error $error, $assertionName, $sequenceName, $requestName)
    {
        $this->errors[] = $error;

        $msg = 'Error for %s/%s/%s. Got "%s" instead of "%s"';
        $msg = sprintf($msg,
            $sequenceName,
            $requestName,
            $assertionName,
            $error->getActual(),
            $error->getExpected()
        );
        $this->output->writeln("<error>$msg</error>");
    }

    public function addErrors(array $results, $assertionName, $sequenceName, $requestName)
    {
        foreach($results as $result) {
            $this->addError($result, $assertionName, $sequenceName, $requestName);
        }
    }

    public function addSuccess($assertionName, $sequenceName, $requestName)
    {
        $msg = 'Success for %s/%s/%s';
        $msg = sprintf($msg,
            $sequenceName,
            $requestName,
            $assertionName
        );
        $this->output->writeln("<info>$msg</info>");
    }
}