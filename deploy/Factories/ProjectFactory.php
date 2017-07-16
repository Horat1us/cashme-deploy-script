<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/16/17
 * Time: 6:23 PM
 */

namespace Horat1us\Deploy\Factories;


use Horat1us\Deploy\Configs\ProjectConfig;
use Horat1us\Deploy\Exceptions\NotGitRepositoryException;
use Horat1us\Deploy\Exceptions\ProjectException;
use Horat1us\Deploy\Exceptions\ProjectNotFoundException;
use Horat1us\Deploy\Project;

/**
 * Class ProjectFactory
 * @package Horat1us\Deploy\Factories
 */
class ProjectFactory
{
    /**
     * Full path to project
     * @var string
     */
    public $path;

    /**
     * Config filename in project directory
     * @var string
     */
    public $configName;

    /**
     * Full path to project configuration
     * @var string
     */
    public $configPath = '.deploy.yml';

    /**
     * ProjectFactory constructor.
     * @param array|string $config
     */
    public function __construct($config)
    {
        is_string($config)
            ? $this->configure([
            'path' => $config,
        ])
            : $this->configure($config);
    }

    /**
     * @param array $config
     * @throws ProjectException
     * @return $this
     */
    public function configure(array $config)
    {
        if (!array_key_exists('path', $config)) {
            throw new ProjectException($config, 'Project config must contain path to project');
        }

        return configure_object($this, $config);
    }


    /**
     * @throws NotGitRepositoryException
     * @throws ProjectException
     * @throws ProjectNotFoundException
     * @return Project
     */
    public function instantiate()
    {
        $path = rtrim($this->path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        if (!file_exists($path) || !is_dir($path)) {
            throw new ProjectNotFoundException($this->getConfig());
        }

        if (!file_exists($path . '.git') || !is_dir($path . '.git')) {
            throw new NotGitRepositoryException($this->getConfig());
        }

        $configPath = $this->configPath?? ($path . $this->configName);

        if (!file_exists($configPath)) {
            throw new ProjectException($this->getConfig(), "Project does not contain deploy config");
        }

        $config = ProjectConfig::load($configPath);

        return new Project($config);
    }

    public static function create(string $path, array $config = [])
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        if (!file_exists($path) || !is_dir($path)) {
            throw new ProjectNotFoundException($path);
        }

        if (!file_exists($path . '.git') || !is_dir($path . '.git')) {
            throw new NotGitRepositoryException($path);
        }

        $configPath = $config['configPath']
            ?? ($path . ($config['configName'] ?? '.deploy.yaml'));

        if (!file_exists($configPath)) {
            throw new ProjectException($path, '');
        }
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return get_object_vars($this);
    }
}