<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 6:31 PM
 */

namespace Horat1us\Deploy\Configs;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Config
 * @package Horat1us\Deploy
 */
class AppConfig implements ConfigurationInterface
{
    const ROOT = 'git_deploy';

    /**
     * @param string $root
     * @return array
     */
    public static function load(string $root)
    {
        $configPath = $root . '/config.yaml';
        if (!file_exists($configPath)) {
            throw new InvalidConfigurationException(
                "Config file `config.yaml` does not exists"
            );
        }
        $config = Yaml::parse(file_get_contents($configPath));

        $processor = new Processor();
        $configuration = new static();

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
            ->end()
            ->arrayNode('allowed')
            ->beforeNormalization()
            ->ifEmpty()->thenEmptyArray()
            ->castToArray()
            ->end()
            ->variablePrototype()->end()
            ->end()
            ->end()
            ->end()
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
            ->end()
            ->isRequired()
            ->end()
            ->variableNode('configName')->end()
            ->variableNode('configPath')
            ->beforeNormalization()
            ->ifTrue(function ($v) {
                return !file_exists($v);
            })->thenInvalid('Project file does not exists %s')
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end();


        return $treeBuilder;
    }
}