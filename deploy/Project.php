<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 5:58 PM
 */

namespace Horat1us\Deploy;

use Horat1us\Deploy\Commands\Command;
use Horat1us\Deploy\Factories\CommandFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;


/**
 * Class Project
 * @package Horat1us\Deploy
 */
class Project
{
    const CONFIG_FILE_NAME = '.git_deploy.yaml';

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
     * Can we use file with settings in project
     *
     * @var bool
     */
    public $remoteConfig = false;

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
    public $commands = [];

    /**
     * Lists of changed files after last pull
     *
     * @var string[]
     */
    public $changedFiles;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Project constructor.
     * @param LoggerInterface $logger
     * @param array $config
     */
    public function __construct(LoggerInterface $logger, $config)
    {
        $this->logger = $logger;
        parent::__construct($config);
    }

    /**
     * @return bool
     */
    public function deploy(): bool
    {
        try {
            $process = new Process("git pull {$this->remote} {$this->branch}", $this->path);
            $response = $process
                ->mustRun()
                ->getOutput();
        } catch (ProcessFailedException $ex) {
            $this->logger->alert("Failed to pull in repository", [
                'path' => $this->path,
                'output' => $ex->getProcess()->getOutput(),
            ]);
            return false;
        }

        $this->changedFiles = $this->parseOutput($response);

        if (
            $this->remoteConfig
            && array_multi_search(static::CONFIG_FILE_NAME, $this->changedFiles, 'filename')
        ) {
            $this->reloadSettings();
        }

        $this->executeCommands();
    }

    /**
     * @return void
     */
    protected function executeCommands()
    {
        /** @var Command[] $commands */
        $commands = array_map(function ($command): Command {
            return CommandFactory::instantiate($command);
        }, (array)$this->commands);

        foreach ($commands as $command) {
            $command->execute($this);
        }
    }

    /**
     * @return void
     */
    protected function reloadSettings()
    {
        $filePath = $this->path . DIRECTORY_SEPARATOR . static::CONFIG_FILE_NAME;
        if (file_exists($filePath)) {
            return;
        }

        $config = Yaml::parse(file_get_contents($filePath));
        Application::configure($this, $config, ['path', 'remote', 'branch', 'changedFiles']);
    }

    /**
     * @param string $input
     * @return array
     */
    protected function parseOutput(string $input): array
    {
        $match = preg_match_all('/(delete|create) mode (\d+) (.+)/', $input, $matches);

        if (!$match) {
            return [];
        }
        $output = [];
        foreach ($matches[0] as $matchIndex => $fullMatch) {
            $output[$matchIndex] = [
                'mode' => $matches[1][$matchIndex],
                'permissions' => $matches[2][$matchIndex],
                'filename' => $matches[3][$matchIndex],
            ];
        }

        return $output;
    }
}