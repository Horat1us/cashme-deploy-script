<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/29/17
 * Time: 7:22 PM
 */

namespace Horat1us\Deploy\Models\Git;


/**
 * Class GitError
 * @package Horat1us\Deploy\Models\Git
 */
class GitError extends GitResponse
{
    /**
     * @var string
     */
    public $message;

    /**
     * GitError constructor.
     * @param string $message
     * @param string $output
     */
    public function __construct(string $message, string $output)
    {
        $this->message = $message;
        parent::__construct($output);
    }
}