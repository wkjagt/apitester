<?php

namespace ApiTester\Config;

use Exception;

class Validator implements ValidatorInterface
{
    protected $jsonSchemaFile;

    protected $output;

    public function __construct($jsonSchemaFile, $output)
    {
        $this->jsonSchemaFile = $jsonSchemaFile;
        $this->output = $output;

        $this->retriever = new \JsonSchema\Uri\UriRetriever;
        $this->validator = new \JsonSchema\Validator();
    }


    public function validate(array $config)
    {
        $filePath = 'file://' . realpath($this->jsonSchemaFile);
        $schema = $this->retriever->retrieve($filePath);
        $this->validator->check(json_decode(json_encode($config)), $schema);


        var_dump($this->validator->isValid());

        if(false === $this->validator->isValid()) {
            $errors = $this->validator->getErrors();
            $this->output->writeln(sprintf(
                '<error>%d errors found in your test file:</error>', count($errors)));

            foreach ($this->validator->getErrors() as $error) {
                $this->output->writeln(
                    sprintf("<error>[%s] %s</error>", $error['property'], $error['message']));
            }

            throw new Exception('Errors in the test file');
        }
    }

}