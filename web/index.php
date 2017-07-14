<?php

define('__ROOT__', dirname(__DIR__));
require(__ROOT__ . '/vendor/autoload.php');

$response = \Horat1us\Deploy\Application::run(
    $_GET['project'] ?? null
);
$response->push();