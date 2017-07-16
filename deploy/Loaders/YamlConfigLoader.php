<?php

namespace Horat1us\Deploy\Loaders;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlConfigLoader
 * @package Horat1us\Deploy\Loaders
 */
abstract class YamlConfigLoader extends FileLoader
{
    /**
     * Returns object instance that will be configured
     *
     * @return ConfigurationInterface
     */
    abstract protected function getTarget(): ConfigurationInterface;

    /**
     * Loads a resource.
     *
     * @param mixed $resource The resource
     * @param string|null $type The resource type or null if unknown
     * @return ConfigurationInterface
     *
     * @throws \Exception If something went wrong
     */
    public function load($resource, $type = null)
    {
        $config = Yaml::parse(file_get_contents($resource));
        $target = $this->getTarget();
        $tree = $target->getConfigTreeBuilder()->buildTree();

        return configure_object(
            $target,
            $tree->normalize($config[$tree->getName()])
        );
    }

    /**
     * Returns whether this class supports the given resource.
     *
     * @param mixed $resource A resource
     * @param string|null $type The resource type or null if unknown
     *
     * @return bool True if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
                $resource,
                PATHINFO_EXTENSION
            );
    }
}