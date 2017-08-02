<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 5:54 PM
 */

namespace Horat1us\Deploy;

use Horat1us\Deploy\Services\AccessService;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class Application
 * @package Horat1us\Deploy
 */
class Application implements ConfigurationInterface
{
    const ROOT = 'git_deploy';

    /**
     * @var array
     */
    public $access = [];

    /**
     * @var array
     */
    public $projects = [];

    /**
     * Application constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        configure_object($this, $config);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function run(Request $request): Response
    {
        $access = new AccessService($this->access);

        if (!$access->isAllowed($request->getClientIp())) {
            return new Response('Not allowed', 403);
        }

        if (!($project = $this->getProject($request->get('project'))) instanceof Project) {
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
     * @param string $name
     * @return Project|null
     */
    public function getProject(string $name)
    {
        foreach ($this->projects as $route => $config) {
            if ($route === $name) {
                return new Project($config);
            }
        }
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $root = $treeBuilder->root(static::ROOT);
        $root
            ->children()
                ->arrayNode('access')
                    ->children()
                        ->arrayNode('forbidden')
                            ->beforeNormalization()
                                ->ifEmpty()->thenEmptyArray()
                                ->castToArray()
                            ->end()
                            ->variablePrototype()->end()
                        ->end() // Forbidden
                        ->arrayNode('allowed')
                            ->beforeNormalization()
                                ->ifEmpty()->thenEmptyArray()
                                ->castToArray()
                            ->end()
                            ->variablePrototype()->end()
                        ->end() // Allowed
                    ->end() // Access children
                ->end() // Access
                ->arrayNode('projects')
                    ->isRequired()
                    ->arrayPrototype()
                        ->beforeNormalization()
                            ->ifString()->then(function ($v) {
                                return [
                                    'path' => $v,
                                ];
                            })
                        ->end()
                        ->children()
                            ->variableNode('path')
                                ->beforeNormalization()
                                    ->ifTrue(function ($v) {
                                        return !file_exists($v);
                                    })->thenInvalid("Project does not exists %s")
                                    ->ifTrue(function ($v) {
                                        return !file_exists($v . DIRECTORY_SEPARATOR . '.git');
                                    })->thenInvalid("%s is not a git directory")
                                ->end() // Normalization
                                ->isRequired()
                            ->end() // Path
                            ->variableNode('configName')->end()
                            ->variableNode('configPath')
                                ->beforeNormalization()
                                    ->ifTrue(function ($v) {
                                        return !file_exists($v);
                                    })->thenInvalid('Project file does not exists %s')
                                ->end()
                            ->end() // Config Path
                        ->end() // Children
                    ->end() // Array prototype
                ->end() // Projects
            ->end();


        return $treeBuilder;
    }
}