<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 8:42 PM
 */

namespace Horat1us\Deploy;


/**
 * Class Trigger
 * @package Horat1us\Deploy
 */
class Trigger
{
    /**
     * @var string[]
     */
    public $paths;

    /**
     * @var bool
     */
    public $exact = false;

    /**
     * @param array $changedFiles
     * @return bool
     */
    public function triggered(array $changedFiles)
    {
        foreach ($changedFiles as $changedFile) {
            if ($this->exact && array_multi_search($changedFile, (array)$this->paths, 'filename')) {
                return true;
            } elseif ($this->itemIncludes((array)$this->paths, $changedFile)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Does at least one element filename contains this path
     *
     * @param array $items
     * @param string $needle
     * @return bool
     */
    public function itemIncludes(array $items, string $needle)
    {
        foreach ($items as $item) {
            if (preg_match($needle, $item)) {
                return true;
            }
        }

        return false;
    }
}