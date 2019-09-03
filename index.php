<?php

use lh\classes\Application;

session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 1);

$loader = require(__DIR__.'/vendor/autoload.php');
$loader->addPsr4('lh\\', __DIR__.'/');
$application = new Application();
$application->run();
