<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/29/17
 * Time: 7:22 PM
 */

namespace Horat1us\Deploy\Models\Git;

/**
 * Class GitResponse
 * @package Horat1us\Deploy\Models\Git
 */
class GitResponse
{
    /**
     * @var string
     */
    public $output;

    /**
     * GitResponse constructor.
     *
     * @param string $output
     */
    public function __construct(string $output)
    {
        $this->output = $output;
    }
}