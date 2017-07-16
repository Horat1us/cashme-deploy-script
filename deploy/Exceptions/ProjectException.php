<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/16/17
 * Time: 6:29 PM
 */

namespace Horat1us\Deploy\Exceptions;


use Throwable;

/**
 * Class ProjectException
 * @package Horat1us\Deploy\Exceptions
 */
class ProjectException extends Exception
{
    /**
     * @var string
     */
    protected $config;

    public function __construct(array $config, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->config = $config;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}