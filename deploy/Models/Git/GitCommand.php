<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/29/17
 * Time: 7:22 PM
 */

namespace Horat1us\Deploy\Models\Git;

use Horat1us\Deploy\Exceptions\GitException;
use Horat1us\Deploy\Exceptions\InvalidGitRepository;
use Horat1us\Deploy\Services\GitExecutionService;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;


/**
 * Class GitCommand
 * @package Horat1us\Deploy\Models\Git
 */
class GitCommand
{
    /**
     * @var string
     */
    protected $command;

    /**
     * @var string
     */
    protected $path;

    /**
     * GitCommand constructor.
     *
     * @param string $command
     * @param string $path
     */
    public function __construct(string $command, string $path)
    {
        $this->setCommand($command);
        $this->setPath($path);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @throws InvalidGitRepository
     * @throws FileNotFoundException
     */
    public function setPath(string $path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException(null, 0, null, $path);
        }
        if (!file_exists($path . '/.git')) {
            throw new InvalidGitRepository($path);
        }

        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     * @throws GitException
     */
    public function setCommand(string $command)
    {
        if (!preg_match('/git.+/', $command)) {
            throw new GitException("Invalid command!");
        }
        $this->command = $command;
    }


    /**
     * Executing `pull` from `git pull origin master`
     *
     * @return string
     */
    public function getType(): string
    {
        preg_match('/git\s([\w\-]+)/', $this->command, $matches);
        return $matches[1];
    }

    /**
     * @return GitResponse
     */
    public function execute(): GitResponse
    {
        return (new GitExecutionService($this))->execute();
    }
}