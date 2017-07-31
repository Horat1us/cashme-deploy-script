<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/16/17
 * Time: 7:23 PM
 */

namespace Horat1us\Deploy\Loaders;


use Horat1us\Deploy\Project;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class ProjectLoader
 * @package Horat1us\Deploy\Loaders
 */
class ProjectLoader extends YamlConfigLoader
{

    /**
     * Returns object instance that will be configured
     *
     * @return ConfigurationInterface
     */
    protected function getTarget(): ConfigurationInterface
    {
        return new Project();
    }
}