<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 5:54 PM
 */

namespace Horat1us\Deploy;

use Symfony\Component\Yaml\Yaml;


/**
 * Class Application
 * @package Horat1us\Deploy
 */
class Application
{
    /**
     * @var Application
     */
    protected static $instance;

    /**
     * @param array ...$args
     * @return Response
     */
    public static function run(...$args): Response
    {
        static::$instance = static::$instance ?? new static(...$args);
        return static::$instance->getResponse();
    }

    /**
     * @var string
     */
    public $route;

    /**
     * @var array
     */
    public $config;

    /**
     * Application constructor.
     * @param string|null $route
     */
    public function __construct(string $route = null)
    {
        $this->route = $route;
        $config = new Config();
        $this->config = $config->get();
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        $access = new Access($this->config['access']);

        if (!$access->isAllowed()) {
            return new Response('Not allowed', 403);
        }

        if (!($project = $this->getProject()) instanceof Project) {
            return new Response("Invalid project", 404);
        }

        try {
            if (!$project->deploy()) {
                return new Response("Failed to deploy", 400);
            }
        } catch (\Throwable $ex) {
            return new Response("Internal server error", 500);
        }

        return new Response("Successful", 200);
    }

    /**
     * @return Project|null
     */
    public function getProject()
    {
        foreach ($this->config['projects'] as $route => $config) {
            if ($route === $this->route) {
                return new Project($config);
            }
        }
    }
}