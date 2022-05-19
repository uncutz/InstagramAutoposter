<?php

use Symfony\Component\Console\Application;

require_once 'vendor/autoload.php';

$cli = new Application();

$cli->add(new \Aeon\Cli\PublishPost());

$cli->run();