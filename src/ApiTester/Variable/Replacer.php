<?php

namespace ApiTester\Variable;

class Replacer
{
    protected $variableClasses = [];

    protected $variableReplacers = [];

    public function __construct(array $variableClasses = [])
    {
        foreach($variableClasses as $varName => $class) {
            $this->variableReplacers[$varName] = new $class;
        }
    }

    public function replaceAll(array $variables)
    {
        $ret = [];

        foreach($variables as $key => $value) {
            if(preg_match('/\%(\w+)\%/', $value, $matches)) {

                $variableName = $matches[1];

                if(isset($this->variableReplacers[$variableName])) {
                    $ret[$key] = $this->variableReplacers[$variableName]->replace($value);
                }
            } else {
                $ret[$key] = $value;
            }
        }
        return $ret;
    }
}