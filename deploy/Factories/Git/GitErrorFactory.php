<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/29/17
 * Time: 7:35 PM
 */

namespace Horat1us\Deploy\Factories;

use Horat1us\Deploy\Models\Git\GitError;
use Horat1us\Deploy\Models\Git\GitResponse;


/**
 * Class GitErrorFactory
 * @package Horat1us\Deploy\Factories
 */
class GitErrorFactory extends GitResponseFactory
{

    /**
     * @return GitError|GitResponse
     */
    public function getInstance(): GitResponse
    {
        $isError = preg_match('/error:\s(.*(\n\s+.+)*)/', $this->output, $matches);
        if (!$isError) {
            throw new \UnexpectedValueException("Current output does not contain git errors.");
        }

        return new GitError($matches[1], $this->output);
    }
}