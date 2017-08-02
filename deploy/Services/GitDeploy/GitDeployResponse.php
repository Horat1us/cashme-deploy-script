<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/2/17
 * Time: 2:57 PM
 */

namespace Horat1us\Deploy\Services\GitDeploy;

use Carbon\Carbon;


/**
 * Class GitDeployResponse
 * @package Horat1us\Deploy\Services\GitDeploy
 */
class GitDeployResponse
{
    /**
     * @var string[]
     */
    public $changedFiles;

    /**
     * @var Carbon
     */
    public $timestamp;

    /**
     * GitDeployResponse constructor.
     * @param Carbon $time
     * @param array $changedFiles
     */
    public function __construct(Carbon $time, array $changedFiles = [])
    {
        $this->timestamp = $time;
        $this->changedFiles = $changedFiles;
    }

    /**
     * @param string $file
     * @return bool
     */
    public function isChanged(string $file) :bool
    {
        return array_search($file, $this->changedFiles) !== false;
    }
}