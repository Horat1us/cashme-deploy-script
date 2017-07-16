<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/16/17
 * Time: 9:55 PM
 */

namespace Horat1us\Deploy\Loaders;

use Horat1us\Deploy\Application;
use Symfony\Component\Config\Definition\ConfigurationInterface;


/**
 * Class ApplicationLoader
 * @package Horat1us\Deploy\Loaders
 *
 * @method Application load($resource, $type = null)
 */
class ApplicationYamlLoader extends YamlConfigLoader
{

    /**
     * Returns object instance that will be configured
     *
     * @return ConfigurationInterface
     */
    protected function getTarget(): ConfigurationInterface
    {
        return new Application();
    }
}