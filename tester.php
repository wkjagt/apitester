<?php

require __DIR__.'/vendor/autoload.php';

$console = new Symfony\Component\Console\Application();
$console->add(new ApiTester\Command\RunCommand);
$console->run();