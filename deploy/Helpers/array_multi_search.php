<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 8:23 PM
 */

if (!function_exists('array_multi_search')) {

    /**
     * @param mixed $needle
     * @param array $array
     * @param string|null $key
     * @return mixed|null
     */
    function array_multi_search($needle, array $array, string $key = null)
    {
        foreach ($array as $subArray) {
            if (!is_array($subArray)) {
                continue;
            }
            if (empty($key)
                ? in_array($needle, $subArray)
                : $subArray[$key] ?? null === $needle
            ) {
                return $subArray;
            }
        }
    }
}