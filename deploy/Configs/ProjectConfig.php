<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 11:59 PM
 */

namespace Horat1us\Deploy\Configs;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ProjectConfig
 * @package Horat1us\Deploy\Configs
 */
class ProjectConfig implements ConfigurationInterface
{
    /**
     * @param string $projectConfigPath
     * @return array
     */
    public static function load($projectConfigPath)
    {
        $config = Yaml::parse(file_get_contents($projectConfigPath));
        $processor = new Processor;
        $configuration = new static;

        return $processor->processConfiguration(
            $configuration,
            $config
        );
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
                        ->end()
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
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}