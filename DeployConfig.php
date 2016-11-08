<?php

require_once('Config.php');

/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 07.11.16
 * Time: 19:23
 */

/**
 * Class DeployConfig
 * @property string $remote
 * @property string $branch
 * @property string $rootDir
 * @property string $logPath
 * @property array|bool $allowedIPs
 */
class DeployConfig extends Config
{
    /**
     * DeployConfig constructor.
     * @param array $fields
     */
    public function __construct(array $fields = ['remote', 'branch', 'rootDir', 'allowedIPs', 'logPath'])
    {
        parent::__construct($fields);
    }

    /**
     * @param AccessProvider $accessProvider
     * @return bool
     */
    public function checkAccess(AccessProvider $accessProvider)
    {
        if (!is_array($this->allowedIPs)) {

            return true;
        }

        return $accessProvider->setConfig($this->allowedIPs)->isAllowed();
    }

    /**
     * @param Deploy $deploy
     * @param callable $postDeploy
     * @return $this
     */
    public function setPostDeploy(Deploy &$deploy, callable $postDeploy = null)
    {
        if ($postDeploy) {

            $deploy->post_deploy = file_exists("PostDeploy.php") ? include("PostDeploy.php") : false;

            if (!is_callable($deploy->post_deploy)) {

                throw new UnexpectedValueException("Wrong postDeploy function #" . gettype($deploy->post_deploy));
            }
        }

        $deploy->post_deploy = $postDeploy;

        return $this;
    }
}