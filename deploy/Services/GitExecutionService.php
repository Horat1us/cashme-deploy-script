<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/29/17
 * Time: 7:39 PM
 */

namespace Horat1us\Deploy\Services;


use Horat1us\Deploy\Factories\GitErrorFactory;
use Horat1us\Deploy\Factories\GitResponseFactory;
use Horat1us\Deploy\Models\Git\GitCommand;
use Horat1us\Deploy\Models\Git\GitResponse;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class GitExecutionService
 * @package Horat1us\Deploy\Services
 */
class GitExecutionService
{
    /**
     * @var GitCommand
     */
    protected $command;

    /**
     * GitExecutionService constructor.
     * @param GitCommand $command
     */
    public function __construct(GitCommand $command)
    {
        $this->command = $command;
    }

    /**
     * @return GitCommand
     */
    public function getCommand(): GitCommand
    {
        return $this->command;
    }

    /**
     * @param GitCommand $command
     */
    public function setCommand(GitCommand $command)
    {
        $this->command = $command;
    }

    /**
     * @return GitResponse
     */
    public function execute(): GitResponse
    {
        $process = new Process($this->command->getCommand(), $this->command->getPath());

        try {
            $process->mustRun();
            $factory = new GitResponseFactory($this->command, $process->getOutput());
        } catch (ProcessFailedException $ex) {
            $factory = new GitErrorFactory($this->command, $ex->getProcess()->getOutput());
        }

        return $factory->getInstance();
    }
}