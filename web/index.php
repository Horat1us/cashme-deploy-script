<?php

define('__ROOT__', dirname(__DIR__));
require(__ROOT__ . '/vendor/autoload.php');


$config = \Horat1us\Deploy\Configs\AppConfig::load(__ROOT__);

$app = new \Horat1us\Deploy\Application(
    \Symfony\Component\HttpFoundation\Request::createFromGlobals(),
    $config
);
$app
    ->getResponse()
    ->send();