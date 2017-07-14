<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 5:58 PM
 */

namespace Horat1us\Deploy;

use PHPGit\Exception\GitException;
use PHPGit\Git;
use Psr\Log\LoggerInterface;


/**
 * Class Project
 * @package Horat1us\Deploy
 */
class Project extends Component
{
    /**
     * Path to project
     *
     * @var bool
     */
    public $path;

    /**
     * Git remote
     *
     * @var string
     */
    public $remote = 'origin';

    /**
     * Git branch
     *
     * @var string
     */
    public $branch = 'master';

    /**
     * Should npm packages be installed
     *
     * @var bool
     */
    public $npm;

    /**
     * Should composer packages be installed
     *
     * @var bool
     */
    public $composer;

    /**
     * Scripts to be executed in project folder
     *
     * @var string
     */
    public $scripts;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger, array $config = [])
    {
        $this->logger = $logger;
        parent::__construct($config);
    }


    /**
     * @return bool
     */
    public function deploy(): bool
    {
        if (!file_exists($this->path)) {
            $this->logger->alert("Path to project does not exists!", [
                'path' => $this->path,
            ]);
            return false;
        }
        try {

        } catch (GitException $exception) {
            $this->logger->alert($exception->getMessage(), [
                'code' => $exception->getCode(),
                'commandLine' => $exception->getCommandLine(),
            ]);
        }
    }
}