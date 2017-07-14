<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 6:22 PM
 */

namespace Horat1us\Deploy;

/**
 * Class Component
 * @package Horat1us\Deploy
 */
class Component
{
    /**
     * Component constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            if (empty($value)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }
}