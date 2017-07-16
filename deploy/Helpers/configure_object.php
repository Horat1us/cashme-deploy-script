<?php

if (!function_exists('configure_object')) {

    /**
     * @param object $object
     * @param array $config
     * @param array $exclude
     *
     * @return object
     */
    function configure_object($object, array $config, array $exclude = [])
    {
        foreach ($config as $key => $value) {
            if (empty($value) || in_array($key, $exclude)) {
                continue;
            }

            $object->{$key} = $value;
        }
        return $object;
    }
}