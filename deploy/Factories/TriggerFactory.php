<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 8:49 PM
 */

namespace Horat1us\Deploy\Factories;


use Horat1us\Deploy\Trigger;

/**
 * Class TriggerFactory
 * @package Horat1us\Deploy\Factories
 */
class TriggerFactory
{
    /**
     * @param array $config
     * @return Trigger
     */
    public static function instantiate($config): Trigger
    {
        if (is_string($config)) {
            return static::simple($config);
        }

        $command = new Trigger($config);
        return $command;
    }

    /**
     * @param string $path
     * @return Trigger
     */
    public static function simple(string $path): Trigger
    {
        return new Trigger(['paths' => $path]);
    }
}