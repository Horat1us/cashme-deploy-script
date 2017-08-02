<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/2/17
 * Time: 3:44 PM
 */

namespace Horat1us\Deploy;

use Horat1us\Deploy\Validators\GitPathValidator;


/**
 * Class GitPath
 * @package Horat1us\Deploy
 */
class GitPath
{
    /**
     * @var string
     */
    protected $path;

    /**
     * GitPath constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->setPath($path);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $validator = new GitPathValidator($this->path);
        $validator->validate();

        $this->path = $path;
    }

    /**
     * @return string
     */
    public function __toString() :string
    {
        return $this->path;
    }
}