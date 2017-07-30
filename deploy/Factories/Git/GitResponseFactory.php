<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/29/17
 * Time: 8:09 PM
 */

namespace Horat1us\Deploy\Factories;
use Horat1us\Deploy\Exceptions\GitException;
use Horat1us\Deploy\Models\Git\GitCommand;
use Horat1us\Deploy\Models\Git\GitResponse;


/**
 * Class GitResponseFactory
 * @package Horat1us\Deploy\Factories
 */
class GitResponseFactory
{
    /**
     * @var GitCommand
     */
    public $command;

    /**
     * @var string
     */
    public $output;

    /**
     * GitResponseFactory constructor.
     *
     * @param GitCommand $command
     * @param string $output
     */
    public function __construct(GitCommand $command, string $output)
    {
        $this->command = $command;
        $this->output = $output;
    }

    /**
     * @return GitResponse
     * @throws GitException
     */
    public function getInstance() :GitResponse
    {

    }
}