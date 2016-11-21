<?php

define('ROOT', $_SERVER['DOCUMENT_ROOT'] ?: $_SERVER['PWD'], true);

require_once('autoload.php');

date_default_timezone_set('Europe/Kiev');

/**
 * @var Config $config
 * @property
 */
$config = new DeployConfig();

if (!$config->checkAccess(new Access())) {
    return "Not allowed";
}

$deploy = new Deploy($config->rootDir, ['log' => $config->logPath, 'branch' => $config->branch, 'remote' => $config->remote,]);

$postDeploy = $config->getPostDeploy();

$postDeploy
    ? $deploy->setPostDeploy($postDeploy)
    : $deploy->setPostDeploy();

$deploy->execute();