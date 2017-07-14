<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 6:31 PM
 */

namespace Horat1us\Deploy;
use Symfony\Component\Yaml\Yaml;


/**
 * Class Config
 * @package Horat1us\Deploy
 */
class Config
{
    /**
     * @var string
     */
    public $filePath;

    /**
     * Config constructor.
     * @param string|null $filePath
     */
    public function __construct(string $filePath = null)
    {
        $this->filePath = $filePath ?? $this->getDefaultConfigPath();
    }

    /**
     * @return string
     */
    protected function getDefaultConfigPath(): string
    {
        $file = __ROOT__ . '/config.yaml';
        return file_exists($file)
            ? $file
            : __ROOT__ . '/config.example.yaml';
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $contents = file_get_contents($this->filePath);
        return Yaml::parse($contents);
    }
}