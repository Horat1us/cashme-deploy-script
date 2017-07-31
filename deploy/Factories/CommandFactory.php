<?php

namespace Horat1us\Deploy\Factories;

use Horat1us\Deploy\Commands\Command;

/**
 * Class CommandFactory
 * @package Horat1us\Deploy\Factories
 */
class CommandFactory
{
    /**
     * @param array $config
     * @return Command
     */
    public static function instantiate($config): Command
    {
        if (is_string($config)) {
            return static::simple($config);
        }

        $command = new Command($config);
        return $command;
    }

    /**
     * @param string $command
     * @return Command
     */
    public static function simple(string $command): Command
    {
        return new Command(['command' => $command]);
    }
}