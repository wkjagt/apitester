<?php

namespace ApiTester\Config;

interface FileLoaderInterface
{
    public function load($filePath);

    public function getRawConfig();
}