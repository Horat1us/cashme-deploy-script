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
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;


/**
 * Class Project
 * @package Horat1us\Deploy
 */
class Project implements ConfigurationInterface
{
    /**
     * @immutable
     * @var GitPath
     */
    protected $path;

    /**
     * Project constructor.
     * @param GitPath $path
     */
    public function __construct(GitPath $path)
    {
        $this->path = $path;
    }

    /**
     * @return GitPath
     */
    public function getPath(): GitPath
    {
        return $this->path;
    }

    /**
     * @return void
     */
    public function load()
    {
        $filePath = $this->path . DIRECTORY_SEPARATOR . static::CONFIG_FILE_NAME;
        if (file_exists($filePath)) {
            return;
        }

        $config = Yaml::parse(file_get_contents($filePath));
        configure_object($this, $config, ['path', 'remote', 'branch', 'changedFiles']);
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $root = $treeBuilder->root('git_deploy_project');
        $root->children()
            ->booleanNode('npm')->defaultNull()->end()
            ->booleanNode('composer')->defaultNull()->end()
            ->arrayNode('scripts')
                ->fixXmlConfig('script')
                ->arrayPrototype()
                    ->beforeNormalization()
                        ->ifString()->then(function($v) {
                            return [
                                'command' => $v,
                            ];
                        })
                    ->end()
                    ->children()
                        ->arrayNode('command')
                            ->isRequired()
                            ->beforeNormalization()
                                ->castToArray()
                            ->end()
                            ->variablePrototype()->end()
                        ->end() // Command
                        ->arrayNode('trigger')
                            ->beforeNormalization()
                            ->castToArray()
                            ->end()
                            ->arrayPrototype()
                                ->beforeNormalization()
                                    ->castToArray()
                                    ->ifString()->then(function($v) {
                                        return [
                                            'path' => $v,
                                        ];
                                    })
                                ->end()
                                ->children()
                                    ->variableNode('path')->isRequired()->end()
                                    ->variableNode('trigger')->end()
                                    ->booleanNode('exact')->defaultTrue()->end()
                                ->end()
                            ->end()
                        ->end() // Trigger
                    ->end()
                ->end() // Script Array Prototype
            ->end() // Scripts Array Node
        ->end();

        return $treeBuilder;
    }
}