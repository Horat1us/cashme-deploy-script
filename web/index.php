<?php

require(dirname(__DIR__) . '/bootstrap.php');


$locator = new \Symfony\Component\Config\FileLocator([__ROOT__]);
$loader = new \Horat1us\Deploy\Loaders\ApplicationYamlLoader($locator);
$app = $loader->load(__ROOT__ . '/config.yaml');

$app
    ->run()
    ->send();