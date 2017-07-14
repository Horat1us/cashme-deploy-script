<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 5:54 PM
 */

namespace Horat1us\Deploy;

use Horat1us\Deploy\Configs\AppConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;


/**
 * Class Application
 * @package Horat1us\Deploy
 */
class Application
{
    /**
     * @var array
     */
    public $config;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Application constructor.
     *
     * @param Request $request
     * @param array $config Verified by AppConfig
     * @see AppConfig
     */
    public function __construct(Request $request, array $config)
    {
        $this->request = $request;
        $this->config = $config[AppConfig::ROOT];
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
            if ($route === $this->request->get('project')) {
                return new Project($config);
            }
        }
    }

    /**
     * @param object $object
     * @param array $config
     * @param array $exclude
     *
     * @return object
     */
    public static function configure(object $object, array $config, array $exclude = [])
    {
        foreach ($config as $key => $value) {
            if (empty($value) || in_array($key, $exclude)) {
                continue;
            }

            $object->{$key} = $value;
        }
        return $object;
    }
}