<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/29/17
 * Time: 7:47 PM
 */

namespace Horat1us\Deploy\Exceptions;


use Throwable;

/**
 * Class GitException
 * @package Horat1us\Deploy\Exceptions
 */
class GitException extends Exception
{
    /**
     * @var string Path to git repository (absolute)
     */
    public $path;

    public function __construct(string $path, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->path = $path;

        parent::__construct($message, $code, $previous);
    }
}